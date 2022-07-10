<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Exception;
use App\Helpers\ApiFormat;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Mahasiswa::all();
        if($data){
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }else{
            return ApiFormat::kirimResponse(400,'Tidak Ada Data');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'username'  => 'required',
                'alamat'    => 'required',
            ]);

            $data = Mahasiswa::create([
                'username'  => $request->username,
                'alamat'    => $request->alamat,
            ]);
            
            if($data){
                return ApiFormat::kirimResponse(200,'Data Berhasil Di Tambahkan',$data);
            }else{
                return ApiFormat::kirimResponse(400,'Data Gagal Ditambahkan');
            }
        } catch (Exception $error) {
            return ApiFormat::kirimResponse(400,'Failed',$error);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Mahasiswa::where('id', $id)->get();
        
        if($data){
            return ApiFormat::kirimResponse(200,'Success',$data);
        }else{
            return ApiFormat::kirimResponse(400,'Failed');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'username'  => 'required',
                'alamat'    => 'required',
            ]);

            $mahasiswa = Mahasiswa::findOrFail($id);

            $mahasiswa->update([
                'username'  => $request->username,
                'alamat'    => $request->alamat,
            ]);
            
            $data = Mahasiswa::where('id',$mahasiswa->id)->get();

            if($data){
                return ApiFormat::kirimResponse(200,'Data Berhasil Di Ubah',$data);
            }else{
                return ApiFormat::kirimResponse(400,'Data Gagal Diubah');
            }
        } catch (Exception $error) {
            return ApiFormat::kirimResponse(400,'Failed',$error);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            $data = $mahasiswa->delete();
            if($data){
                return ApiFormat::kirimResponse(200,'Data Berhasil Di Hapus');
            }else{
                return ApiFormat::kirimResponse(400,'Data Gagal DiHapus');
            }
        } catch (Exception $error) {
            return ApiFormat::kirimResponse(400,'Failed',$error);
        }
    }
}
