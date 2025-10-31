<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::all();
        return view('IndexMahasiswa', compact('mahasiswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('createMahasiswa');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'NIM' => 'required|unique:mahasiswas',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jurusan' => 'required',
            'angkatan' => 'required|integer',
        ]);
        Mahasiswa::create([
            'name' => $request->name,
            'NIM' => $request->NIM,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jurusan' => $request->jurusan,
            'angkatan' => $request->angkatan
        ]);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // $mahasiswas = Mahasiswa::with('matakuliah')->findOrFail($id);
        $mahasiswas = Mahasiswa::findorFail($id);
        return view('editMahasiswa', compact('mahasiswas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'NIM' => [
                'required',
                Rule::unique('mahasiswas')->ignore($id),
            ],
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jurusan' => 'required',
            'angkatan' => 'required|integer',
        ]);
        $mahasiswas = Mahasiswa::findOrFail($id);
        $mahasiswas->update($request->all());
        $mahasiswas->matakuliah()->sync($request->matakuliah_id ?? []);

        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil diperbaharui!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mahasiswas = Mahasiswa::findOrFail($id);
        $mahasiswas->delete();

        return redirect('mahasiswa')->with('success', 'Data Mahasiswa berhasil dihapus!');
    }
}
