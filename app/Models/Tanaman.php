<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Tanaman extends Model
{
    protected $fillable = [
        'nama_tanaman',
        'jumlah_benih',
        'tanggal_tanam',
        'estimasi_panen_hari',
        'tanggal_panen_aktual',
        'status',
        'catatan',
        'pupuk_interval_hari',
        'air_interval_hari',
        'terakhir_pupuk',
        'terakhir_air',
    ];

    protected $casts = [
        'tanggal_tanam' => 'date',
        'tanggal_panen_aktual' => 'date',
        'jumlah_benih' => 'integer',
        'estimasi_panen_hari' => 'integer',
        'pupuk_interval_hari' => 'integer',
        'air_interval_hari' => 'integer',
        'terakhir_pupuk' => 'date',
        'terakhir_air' => 'date',
    ];

    protected function progressPanen(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (!$attributes['tanggal_tanam'] || !$attributes['estimasi_panen_hari']) {
                    return 0;
                }
                if ($attributes['tanggal_panen_aktual']) {
                    return 100;
                }

                $tanggalTanam = Carbon::parse($attributes['tanggal_tanam']);
                $estimasiPanenHari = (int) $attributes['estimasi_panen_hari'];
                $tanggalEstimasiPanen = $tanggalTanam->copy()->addDays($estimasiPanenHari);
                $hariBerlalu = $tanggalTanam->diffInDays(now());

                if ($hariBerlalu < 0) return 0;

                // Hitung persentase
                $progress = ($hariBerlalu / $estimasiPanenHari) * 100;

                return min(100, round($progress));
            }
        );
    }

    protected function sisaHariPanen(): Attribute
    {
         return Attribute::make(
            get: function ($value, $attributes) {
                if (!$attributes['tanggal_tanam'] || !$attributes['estimasi_panen_hari'] || $attributes['tanggal_panen_aktual']) {
                    return null;
                }

                $tanggalTanam = Carbon::parse($attributes['tanggal_tanam']);
                $estimasiPanenHari = (int) $attributes['estimasi_panen_hari'];
                $tanggalEstimasiPanen = $tanggalTanam->copy()->addDays($estimasiPanenHari);
                $sisaHari = now()->diffInDays($tanggalEstimasiPanen, false);

                return max(0, round($sisaHari));
            }
         );
    }
    public function getRouteKeyName(): string
    {
        return 'nama_tanaman';
    }

}
