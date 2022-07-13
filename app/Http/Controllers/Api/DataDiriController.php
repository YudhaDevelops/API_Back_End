<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiFormat;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Relawan_Keahlian;
use DB;

class DataDiriController extends Controller
{
    public function getProvinsi(){
        $data = Provinsi::all();
        if($data == null){
            return ApiFormat::responseError(400,'Tidak Ada Data Provinsi');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    public function getKabupaten(){
        $data = Kabupaten::all();
        if($data == null){
            return ApiFormat::responseError(404,'Tidak ada data kabupaten');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    public function getKecamatan(){
        $data = Kecamatan::all();
        if($data == null){
            return ApiFormat::responseError(404,'Tidak ada data kecamatan');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    public function getKelurahan(){
        $data = Kelurahan::all();
        if($data == null){
            return ApiFormat::responseError(404,'Tidak ada data kelurahan');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    // ambil data berdasarkan id
    public function provinsi($id){
        $data = Provinsi::where('id',$id)->first();
        if($data == null){
            return ApiFormat::responseError(400,'Tidak Ada Data Provinsi');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    public function kabupaten($id){
        $data = Kabupaten::where('id',$id)->first();
        if($data == null){
            return ApiFormat::responseError(404,'Tidak ada data kabupaten');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    public function kecamatan($id){
        $data = Kecamatan::where('id',$id)->first();
        if($data == null){
            return ApiFormat::responseError(404,'Tidak ada data kecamatan');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    public function kelurahan($id){
        $data = Kelurahan::where('id',$id)->first();
        if($data == null){
            return ApiFormat::responseError(404,'Tidak ada data kelurahan');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    // coba
    public function coba($id){
        // $data = Provinsi::where('id',$id)->first();
        // $data = Kabupaten::where('id_provinsi',$id)->get();
        // $data = Kecamatan::where('id_kabupaten',$id)->get();
        // $data = Kelurahan::where('id_kecamatan',$id)->get();
        if($data == null){
            return ApiFormat::responseError(404,'Tidak ada data kelurahan');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    // tabel join 3323012010
    public function getAlamat(){
        $kelurahan = Kelurahan::where('id','1806061008')->first();
        $kecamaatan = Kecamatan::where('id',$kelurahan->id_kecamatan)->first();
        $kabupaten = Kabupaten::where('id',$kecamaatan->id_kabupaten)->first();
        $provinsi = Provinsi::where('id',$kabupaten->id_provinsi)->first();

        $data = [
            'kelurahan' => $kelurahan,
            'kecamatan' => $kecamaatan,
            'kabupaten' => $kabupaten,
            'provinsi'  => $provinsi,
        ];
        return $this->sendResponse($data, 'Data Ditemukan');
        // dd($data);

        // $relawan = User::join('verifikasi', 'users.id', '=', 'verifikasi.id_relawan')
        //         ->get(['users.*', 'verifikasi.status_verifikasi']);
    }

    public function tambah_keahlian($id){
        if(Auth::check() == true && auth()->user()->role == 0){
            $idUser =auth()->user()->id;
            Relawan_Keahlian::create([
                'id_relawan'    => $idUser,
                'id_keahlian'   => $id,
            ]);

            $keahlian = Relawan_Keahlian::where('id_relawan',$idUser)->get();
            
            $data = [
                'keahlian_user' => $keahlian,
                'user' => auth()->user(),
            ];
            return $this->sendResponse($data, 'Berhasil ');
        }
        dd($keahlian);
    }

    public function cekKeahlian(){
        // $data = User::join('relawan_keahlian','relawan_keahlian.id_relawan','=','relawan_keahlian.id_relawan')
        //         ->join('keahlian','keahlian.id_keahlian','=','keahlian.id_keahlian')
        //         ->get(['relawan_keahlian.id_relawan','relawan_keahlian.id_keahlian','users.name','keahlian.jenis_keahlian']);

        // $data = Relawan_Keahlian::join('keahlian', 'keahlian.id_keahlian', '=', 'keahlian.id_keahlian')
        //         ->get(['relawan_keahlian.id_relawan','relawan_keahlian.id_keahlian', 'keahlian.jenis_keahlian']);

        $data = User::join('relawan_keahlian','relawan_keahlian.id_relawan','=','users.id')
                ->join('keahlian','keahlian.id_keahlian','=','relawan_keahlian.id_keahlian')
                ->where('id_relawan',3)
                ->get(['relawan_keahlian.id_relawan','relawan_keahlian.id_keahlian','users.name','keahlian.jenis_keahlian']);

        return $this->sendResponse($data, 'Berhasil ');
    }
}
