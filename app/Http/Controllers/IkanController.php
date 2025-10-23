<?php

namespace App\Http\Controllers;

use App\Models\Ikan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class IkanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $ikans = Ikan::latest('tanggal_tebar')->paginate(10);
      return view('content.dashboard.ikan.index',compact('ikans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.dashboard.ikan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validatedData = $request->validate([
            'nama_ikan' => 'required|string|max:255',
            'jumlah_bibit' => 'required|integer|min:1',
            'tanggal_tebar' => 'required|date',
            'estimasi_panen_hari' => 'required|integer|min:1',
            'pakan_interval_jam' => 'nullable|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $validatedData['status'] = 'ditebar';
        try {
            Ikan::create($validatedData);
            return Redirect::route('dashboard-analytics-ikan')
                           ->with('success', 'Ikan Ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan ikan: ' . $e->getMessage());
            return Redirect::back()
                           ->with('error', 'Gagal menambahkan Ikan.')
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ikan $ikan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ikan $ikan)
    {
        return view('content.dashboard.ikan.edit',compact('ikan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ikan $ikan)
    {
        $validatedData = $request->validate([
            'nama_ikan'           => 'required|string|max:255',
            'jumlah_bibit'        => 'required|integer|min:1',
            'tanggal_tebar'       => 'required|date',
            'estimasi_panen_hari' => 'required|integer|min:1',
            'tanggal_panen_aktual'=> 'nullable|date|after_or_equal:tanggal_tebar',
            'status'              => 'required|in:ditebar,dipanen,gagal',
            'catatan'             => 'nullable|string',
        ]);

        // Jika tanggal panen aktual diisi, otomatis set status menjadi 'dipanen'
        if (!empty($validatedData['tanggal_panen_aktual'])) {
            $validatedData['status'] = 'dipanen';
        }

        try {
            $ikan->update($validatedData);
            return Redirect::route('dashboard-analytics-ikan')
                           ->with('success', 'Data ikan berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal update ikan: ' . $e->getMessage());
            return Redirect::back()->with('error', 'Gagal memperbarui data ikan.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ikan $ikan)
    {
        try {
            $ikan->delete();
            return Redirect::route('dashboard-analytics-ikan')
                           ->with('success', 'Data ikan berhasil dihapus.');
        } catch (\Exception $e) {
             Log::error('Gagal menghapus ikan: ' . $e->getMessage());
            return Redirect::route('dashboard-analytics-ikan')
                           ->with('error', 'Gagal menghapus data ikan.');
        }
    }
}
