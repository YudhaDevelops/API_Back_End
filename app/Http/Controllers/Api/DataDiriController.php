<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiFormat;
use DB;
use App\Models\User;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Relawan_Keahlian;
use App\Models\Alamat;
use App\Models\Keahlian;
use App\Models\Pengalaman;
use App\Models\Relawan_Pengalaman;

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
        $data = Kabupaten::where('id_provinsi',$id)->get();
        if($data == null){
            return ApiFormat::responseError(404,'Tidak ada data kabupaten');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    public function kecamatan($id){
        $data = Kecamatan::where('id_kabupaten',$id)->get();
        if($data == null){
            return ApiFormat::responseError(404,'Tidak ada data kecamatan');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    public function kelurahan($id){
        $data = Kelurahan::where('id_kecamatan',$id)->get();
        if($data == null){
            return ApiFormat::responseError(404,'Tidak ada data kelurahan');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }
    
    public function addAlamat(Request $request){
        $alamat_ktp = $this->alamat_ktp($request);
        $alamat_sekarang = $this->alamat_sekarang($request);

    }
    
    // ngisi data di tabel alamat
    private static function alamat_ktp(Request $request){
        try {
            $rules = [
                'detail_alamat'         => 'required|min:5',
                'id_kelurahan'          => 'required|min:10|max:12',
                'kode_pos'              => 'required|size:5'
            ];
    
            $messages = [
                'detail_alamat.required'    => 'Detail aLamat wajib diisi',
                'detail_alamat.min'         => 'Detail alamat Minimal 5 karakter',
                'id_kelurahan.required'     => 'Data Kkelurahan wajib diisi',
                'id_kelurahan.max'          => 'Data kelurahan maksimal 12 Digit Angka',
                'kode_pos.required'         => 'Kode Pos wajib diisi',
                'kode_pos.size'             => 'Kode Pos Harus 5 Digit Angka'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if($validator->fails()){
                return response()->json([
                    'errors' => $validator->errors()->first(),
                ]);
            }

            $data = Alamat::create([
                'detail_alamat'     => $request->detail_alamat,
                'id_kelurahan'      => $request->id_kelurahan,
                'kode_pos'          => $request->kode_pos,
            ]);

            // $data->id ==> di pake untuk masuk ke tabel data diri
            return $data;

        }  catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Add Data Alamat Failed',
            );
        }       
    }

    private static function alamat_sekarang(Request $request){
        try {
            $rules = [
                'detail_alamat'         => 'required|min:5',
                'id_kelurahan'          => 'required|min:10|max:12',
                'kode_pos'              => 'required|size:5'
            ];
    
            $messages = [
                'detail_alamat.required'    => 'Detail aLamat wajib diisi',
                'detail_alamat.min'         => 'Detail alamat Minimal 5 karakter',
                'id_kelurahan.required'     => 'Data Kkelurahan wajib diisi',
                'id_kelurahan.max'          => 'Data kelurahan maksimal 12 Digit Angka',
                'kode_pos.required'         => 'Kode Pos wajib diisi',
                'kode_pos.size'             => 'Kode Pos Harus 5 Digit Angka'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if($validator->fails()){
                return response()->json([
                    'errors' => $validator->errors()->first(),
                ]);
            }

            $data = Alamat::create([
                'detail_alamat'     => $request->detail_alamat,
                'id_kelurahan'      => $request->id_kelurahan,
                'kode_pos'          => $request->kode_pos,
            ]);

            // $data->id ==> di pake untuk masuk ke tabel data diri
            return $data;

        }  catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Add Data Alamat Failed',
            );
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
        return ApiFormat::responseError(404,'Tidak ada hak akses');
    }

    public function delete_keahlian($id){//id yang di pasing id keahlian
        if(Auth::check() == true && auth()->user()->role == 0){
            $idUser = auth()->user()->id;

            $data= Relawan_Keahlian::where('id_relawan',$idUser)
                                        ->where('id_keahlian',$id)
                                        ->delete();
            if($data == 0){
                return ApiFormat::responseError(500,'Gagal menghapus');
            }

            $keahlian = Relawan_Keahlian::where('id_relawan',$idUser)->get();

            $data = [
                'keahlian_user' => $keahlian,
                'user' => auth()->user(),
            ];
            return $this->sendResponse($data, 'Berhasil Dihapus');
        }
        return ApiFormat::responseError(404,'Tidak ada hak akses');
    }

    public function tambah_item_keahlian(Request $request){
        try {
            $rules = [
                'jenis_keahlian'    => 'required|string|min:3|max:25|unique:keahlian',
            ];
    
            $messages = [
                'jenis_keahlian.required'         => 'Jenis keahlian wajib diisi',
                'jenis_keahlian.min'              => 'Jenis keahlian minimal 3 karakter',
                'jenis_keahlian.max'              => 'Jenis keahlian maksimal 64 karakter',
                'jenis_keahlian.unique'           => 'Jenis keahlian sudah Terdaftar'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if($validator->fails()){
                return response()->json([
                    'errors' => $validator->errors()->first(),
                ]);
            }

            $data = Keahlian::create([
                'jenis_keahlian' => $request->jenis_keahlian,
            ]);
            return $this->sendResponse($data, 'Berhasil Ditambahkan');

        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Tambah Item Keahlian Failed',
            );
        }
    }
    

    public function cekKeahlian(){
        // $data = User::join('relawan_keahlian','relawan_keahlian.id_relawan','=','relawan_keahlian.id_relawan')
        //         ->join('keahlian','keahlian.id_keahlian','=','keahlian.id_keahlian')
        //         ->get(['relawan_keahlian.id_relawan','relawan_keahlian.id_keahlian','users.name','keahlian.jenis_keahlian']);

        // $data = Relawan_Keahlian::join('keahlian', 'keahlian.id_keahlian', '=', 'keahlian.id_keahlian')
        //         ->get(['relawan_keahlian.id_relawan','relawan_keahlian.id_keahlian', 'keahlian.jenis_keahlian']);

        $data = User::join('relawan_keahlian','relawan_keahlian.id_relawan','=','users.id')
                ->join('keahlian','keahlian.id_keahlian','=','relawan_keahlian.id_keahlian')
                ->where('id_relawan',2)
                ->get(['relawan_keahlian.id_relawan','relawan_keahlian.id_keahlian','users.nama_lengkap','keahlian.jenis_keahlian']);

        return $this->sendResponse($data, 'Berhasil ');
    }


    // pengalaman
    public function tambahPengalaman(Request $request){
        if(Auth::check() == true && auth()->user()->role == 0){
            $idUser =auth()->user()->id;
            try {
                $rules = [
                    'nama_instansi'    => 'required|string|min:3|max:50',
                    'durasi_waktu'     => 'required|'
                ];
        
                $messages = [
                    'nama_instansi.required'         => 'Nama Instansi wajib diisi',
                    'nama_instansi.min'              => 'Nama Instansi minimal 3 karakter',
                    'nama_instansi.max'              => 'Nama Instansi maksimal 64 karakter',
                    'durasi_waktu.required'          => 'Durasi waktu wajib diisi',
                ];
        
                $validator = Validator::make($request->all(), $rules, $messages);
        
                if($validator->fails()){
                    return response()->json([
                        'errors' => $validator->errors()->first(),
                    ]);
                }
    
                $data = $this->addPengalaman($request);

                Relawan_Pengalaman::create([
                    'id_relawan'    => $idUser,
                    'id_pengalaman' => $data->id,
                ]);
    
                $pengalaman = Relawan_Pengalaman::where('id_relawan',$idUser)->get();
                
                $data = [
                    'pengalaman_user' => $pengalaman,
                    'user' => auth()->user(),
                ];
                return $this->sendResponse($data, 'Berhasil');
    
            } catch (Exception $error) {
                return $this->sendError(
                    [
                        'message' => 'Something went wrong',
                        'error' => $error
                    ],
                    'Tambah Pengalaman Failed',
                );
            }
        }
        return ApiFormat::responseError(404,'Tidak ada hak akses');
    }

    private static function addPengalaman(Request $request){
        try {
            $data = Pengalaman::create([
                'nama_instansi' => $request->nama_instansi,
                'durasi_waktu'  => $request->durasi_waktu,
            ]);

            return $data;

        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Tambah Pengalaman Failed',
            );
        }
    }

    public function deletePengalaman(){
        if(Auth::check() == true && auth()->user()->role == 0){
            
            $relawan_pengalaman = User::join('relawan_pengalaman', 'relawan_pengalaman.id_relawan', '=', 'users.id')
                        ->join('pengalaman','pengalaman.id','=','relawan_pengalaman.id_pengalaman')
                        ->where('id_relawan', auth()->user()->id)
                        ->first();
        
            if(!empty($relawan_pengalaman)){
                $relasiPengalaman = Relawan_Pengalaman::where('id_relawan',$relawan_pengalaman->id_relawan)
                                    ->where('id_pengalaman',$relawan_pengalaman->id_pengalaman)->delete();
                if($relasiPengalaman != 1){
                    return ApiFormat::responseError(500,'Data relasi relawan & pengalaman gagal dihapus');
                }
                $pengalaman = Pengalaman::where('id',$relawan_pengalaman->id_pengalaman)->delete();
                if($pengalaman != 1){
                    return ApiFormat::responseError(500,'Data pengalaman gagal dihapus');
                }
                return $this->sendResponse('Data Berhasil Dihapus');
            }
        return ApiFormat::responseError(404,'Tidak ada data');

        }
    }
}
