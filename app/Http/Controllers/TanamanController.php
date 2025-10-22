<?php

namespace App\Http\Controllers;

use App\Models\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class TanamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $tanamans = Tanaman::latest('tanggal_tanam')->paginate(10);
      return view('content.dashboard.tanaman.index',compact('tanamans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.dashboard.tanaman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            'nama_tanaman' => 'required|string|max:191',
            'jumlah_benih' => 'required|integer|min:1',
            'tanggal_tanam' => 'required|date',
            'estimasi_panen_hari' => 'required|integer|min:1',
            'pupuk_interval_hari' => 'nullable|integer|min:1',
            'air_interval_hari'   => 'nullable|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        // 2. Tambahkan status default (jika perlu)
        // $validatedData['status'] = 'ditanam';

        // 3. Simpan ke Database
        Tanaman::create($validatedData);

        // 4. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('dashboard-analytics-tanaman') // Gunakan nama rute index Anda
                         ->with('success', 'tanaman berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tanaman $tanaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tanaman $tanaman)
    {
      return view('content.dashboard.tanaman.edit', compact('tanaman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tanaman $tanaman)
    {
        $validatedData = $request->validate([
            'nama_tanaman'        => 'required|string|max:191',
            'jumlah_benih'        => 'required|integer|min:1',
            'tanggal_tanam'       => 'required|date',
            'estimasi_panen_hari' => 'required|integer|min:1',
            'tanggal_panen_aktual'=> 'nullable|date|after_or_equal:tanggal_tanam',
            'pupuk_interval_hari' => 'nullable|integer|min:1',
            'air_interval_hari'   => 'nullable|integer|min:1',
            'status'              => 'required|in:ditanam,dipanen,gagal',
            'catatan'             => 'nullable|string',
        ]);

        if (!empty($validatedData['tanggal_panen_aktual'])) {
            $validatedData['status'] = 'dipanen';
        }

        try {
            $tanaman->update($validatedData);
            return Redirect::route('dashboard-analytics-tanaman')
                           ->with('success', 'tanaman diperbarui!');

        } catch (\Exception $e) {

             Log::error('Gagal update tanaman: ' . $e->getMessage());
            return Redirect::back()
                           ->with('error', 'Tanaman Gagal Diperbarui.')
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tanaman $tanaman)
    {
        try {
            $tanaman->delete();
            return redirect()->route('dashboard-analytics-tanaman')
                             ->with('success', 'tanaman dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard-analytics-tanaman')
                             ->with('error', 'Gagal menghapus tanaman.');
        }
    }
}
