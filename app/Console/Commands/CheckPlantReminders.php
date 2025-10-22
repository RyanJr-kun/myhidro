<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tanaman;
use App\Models\User;
use App\Notifications\PlantReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class CheckPlantReminders extends Command
{
    /**
     * Nama dan signature dari command console.
     */
    protected $signature = 'plant:check-reminders';

    /**
     * Deskripsi command console.
     */
    protected $description = 'Periksa tanaman yang memerlukan pengingat pemupukan atau cek air dan kirim notifikasi';

    /**
     * Jalankan command console.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan pengingat tanaman...');
        $today = Carbon::today();
        $sentCount = 0;

        $adminUser = User::first();
        if (!$adminUser) {
            $this->error('Tidak ditemukan user untuk dikirimi notifikasi.');
            return 1;
        }

        $tanamanPerluPupuk = Tanaman::whereNotNull('pupuk_interval_hari')
                                    ->where('status', 'ditanam')
                                    ->get();

        foreach ($tanamanPerluPupuk as $tanaman) {
            $tanggalTerakhir = $tanaman->terakhir_pupuk ?? $tanaman->tanggal_tanam;
            $tanggalJatuhTempo = Carbon::parse($tanggalTerakhir)->addDays($tanaman->pupuk_interval_hari);

            if ($today->isSameDayOrAfter($tanggalJatuhTempo)) {
                $this->line("- Mengirim pengingat pupuk untuk: {$tanaman->nama_tanaman}");

                Notification::send($adminUser, new PlantReminderNotification($tanaman, 'pupuk'));
                $sentCount++;

                $tanaman->terakhir_pupuk = $today;
                $tanaman->save();
            }
        }

         $tanamanPerluAir = Tanaman::whereNotNull('air_interval_hari')
                                    ->where('status', 'ditanam')
                                    ->get();

        foreach ($tanamanPerluAir as $tanaman) {
            $tanggalTerakhir = $tanaman->terakhir_air ?? $tanaman->tanggal_tanam;
            $tanggalJatuhTempo = Carbon::parse($tanggalTerakhir)->addDays($tanaman->air_interval_hari);

            if ($today->isSameDayOrAfter($tanggalJatuhTempo)) {
                 $this->line("- Mengirim pengingat cek air untuk: {$tanaman->nama_tanaman}");
                Notification::send($adminUser, new PlantReminderNotification($tanaman, 'air'));
                $sentCount++;
                $tanaman->terakhir_air = $today;
                $tanaman->save();
            }
        }

        $this->info("Pengecekan selesai. {$sentCount} notifikasi dikirim.");
        return 0;
      }
}
