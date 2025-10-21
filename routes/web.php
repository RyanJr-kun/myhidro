<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IkanController;
use App\Http\Controllers\TanamanController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\Api\PumpApiController;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\pages\AccountSettingsSecurity;
use App\Http\Controllers\sistem_control\PumpController;
use App\Http\Controllers\pages\AccountManagementController;
use App\Http\Controllers\authentications\ResetPasswordBasic;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\sistem_control\PumpHistoryController;
use App\Http\Controllers\sistem_control\PumpScheduleController;
use App\Http\Middleware\VerifyDeviceToken;

Route::middleware('auth:sanctum')->group(function () {
    // 1. Menggantikan Page/GetPumpStatus.php
    Route::get('/pump/status', [PumpApiController::class, 'getManualStatusForWeb']);
    // 2. Menggantikan Page/ApiKontrolPompaweb.php
    Route::post('/pump/toggle', [PumpApiController::class, 'togglePumpStatus']);
});

// --- Rute untuk NodeMCU/Arduino ---
// Rute ini diamankan dengan token khusus
Route::middleware(VerifyDeviceToken::class)->group(function () {
    // 3. Menggantikan Hidroponik/ApiKontrolPompaArduino.php
    Route::get('/arduino/pump-status', [PumpApiController::class, 'getManualStatusForArduino']);
    // 4. Menggantikan Hidroponik/GetJadwalArduino.php
    Route::get('/arduino/schedules', [PumpApiController::class, 'getSchedulesForArduino']);
});

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('login')->middleware('guest');
Route::post('/auth/login-basic', [LoginBasic::class, 'authenticate'])->name('login-authenticate');

Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::post('/auth/register-basic', [RegisterBasic::class, 'store'])->name('auth-register-store');
//verifi email kalo mau tulis disini

Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordBasic::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordBasic::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordBasic::class, 'update'])->name('password.update');
Route::post('/auth/logout',[LoginBasic::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

  Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');
  Route::resource('/tanaman', TanamanController::class)->except('show','create','edit')->names([
    'index' => 'dashboard-analytics-tanaman',
    'create' => 'tanaman.create',
    'store' => 'tanaman.store'
  ]);
  Route::resource('/ikan', IkanController::class)->except('show','create','edit')->names([
    'index' => 'dashboard-analytics-ikan',
    'create' => 'ikan.create',
    'store' => 'ikan.store'
  ]);
  Route::get('/sistem-control/pump-control', [PumpController::class, 'index'])->name('sistem-pump-control');
  Route::post('/sistem-control/pump-status/{pump}', [PumpController::class, 'updateStatus'])->name('sistem-pump-status');
  Route::get('/sistem-control/pump-schedule', [PumpScheduleController::class, 'index'])->name('sistem-pump-schedule');
  Route::post('/sistem-control/pump-schedule', [PumpScheduleController::class, 'store'])->name('sistem-pump-schedule.store');
  Route::get('/sistem-control/pump-history', [PumpHistoryController::class, 'index'])->name('sistem-pump-history');
  Route::get('/riwayat-pompa/export/excel', [PumpHistoryController::class, 'exportExcel'])->name('kontrol-riwayat-pompa-excel');
  Route::get('/riwayat-pompa/export/pdf', [PumpHistoryController::class, 'exportPdf'])->name('kontrol-riwayat-pompa-pdf');

  Route::get('/pages/account-settings', [AccountSettingsAccount::class, 'index'])->name('account-settings');
  Route::post('/pages/account-settings', [AccountSettingsAccount::class, 'update'])->name('account-settings.update');
  Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('account-settings-notifications');
  Route::get('/pages/account-settings-security', [AccountSettingsSecurity::class, 'index'])->name('account-settings-security');
  Route::post('/pages/account-settings-security', [AccountSettingsSecurity::class, 'updatePassword'])->name('account-settings-security.update');
});
Route::middleware(['admin', 'auth'])->group(function () {
  Route::get('/pages/account-management', [AccountManagementController::class, 'index'])->name('account-management');
  Route::post('/pages/account-management', [AccountManagementController::class, 'store'])->name('account-management.store');
  Route::put('/pages/account-management/{id}', [AccountManagementController::class, 'update'])->name('account-management.update');
  Route::delete('/pages/account-management/{id}', [AccountManagementController::class, 'destroy'])->name('account-management.destroy');
});
