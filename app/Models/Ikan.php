<?php

namespace App\Models; // Sesuaikan namespace

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Ikan extends Model
{
    // protected $table = 'ikans'; // Sesuaikan jika nama tabel berbeda

    protected $fillable = [
        'nama_ikan',
        'jumlah_bibit',
        'tanggal_tebar',
        'estimasi_panen_hari',
        'tanggal_panen_aktual',
        'status',
        'pakan_interval_jam',
        'terakhir_pakan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_tebar' => 'datetime', // Atau 'date'
        'tanggal_panen_aktual' => 'datetime', // Atau 'date'
        'jumlah_bibit' => 'integer',
        'estimasi_panen_hari' => 'integer',
        'pakan_interval_jam' => 'integer',
        'terakhir_pakan' => 'datetime',
    ];

    // Accessor untuk progress panen ikan (mirip tanaman)
    protected function progressPanen(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (!$attributes['tanggal_tebar'] || !$attributes['estimasi_panen_hari']) return 0;
                if ($attributes['tanggal_panen_aktual']) return 100;

                $tanggalTebar = Carbon::parse($attributes['tanggal_tebar']);
                $estimasiPanenHari = (int) $attributes['estimasi_panen_hari'];
                $hariBerlalu = $tanggalTebar->diffInDays(now());

                if ($hariBerlalu < 0) return 0;
                $progress = ($hariBerlalu / $estimasiPanenHari) * 100;
                return min(100, round($progress));
            }
        );
    }

     // Accessor untuk sisa hari panen ikan (mirip tanaman)
    protected function sisaHariPanen(): Attribute
    {
         return Attribute::make(
            get: function ($value, $attributes) {
                if (!$attributes['tanggal_tebar'] || !$attributes['estimasi_panen_hari'] || $attributes['tanggal_panen_aktual']) return null;

                $tanggalTebar = Carbon::parse($attributes['tanggal_tebar']);
                $estimasiPanenHari = (int) $attributes['estimasi_panen_hari'];
                $tanggalEstimasiPanen = $tanggalTebar->copy()->addDays($estimasiPanenHari);
                $sisaHari = now()->diffInDays($tanggalEstimasiPanen, false);

                return max(0, round($sisaHari));
            }
         );
    }

    public function getRouteKeyName(): string
    {
        return 'nama_ikan';
    }
}
