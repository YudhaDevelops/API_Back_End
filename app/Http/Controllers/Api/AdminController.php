<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sesi_User;
use App\Models\Verifikasi;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiFormat;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon;

class AdminController extends Controller
{
    public function index(){
        $relawan = User::join('verifikasi', 'users.id', '=', 'verifikasi.id_relawan')
                ->get(['users.*', 'verifikasi.status_verifikasi']);
        if(empty($relawan)){
            return ApiFormat::responseError(400,'Tidak Ada Data');
        }else{
            $relawan = User::join('verifikasi', 'users.id', '=', 'verifikasi.id_relawan')
                ->get(['users.*', 'verifikasi.status_verifikasi']);
            return ApiFormat::responPerSatuObject(200,'Data Ditemukan',$relawan);
        }
    }

    public function tambah_admin(Request $request){
        try {
            $rules = [
                'name'                  => 'required|string|min:3|max:64',
                'email'                 => 'required|email|unique:users',
                'password'              => 'required|min:6|confirmed'
            ];
    
            $messages = [
                'name.required'         => 'Nama Lengkap wajib diisi',
                'name.min'              => 'Nama lengkap minimal 3 karakter',
                'name.max'              => 'Nama lengkap maksimal 64 karakter',
                'email.required'        => 'Email wajib diisi',
                'email.email'           => 'Email tidak valid',
                'email.unique'          => 'Email sudah terdaftar',
                'password.required'     => 'Password wajib diisi',
                'password.min'          => 'Password minimal 6 karakter',
                'password.confirmed'    => 'Password tidak sama dengan konfirmasi password'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if($validator->fails()){
                return response()->json([
                    'errors' => $validator->errors()->first(),
                ]);
            }
            
            User::create([
                'name' => $request->name,
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'role'  => 1,
            ]);

            $user = User::where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            $data = [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ];
            return $this->sendResponse($data, 'Successfull Add New Admin');
        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Add New Admin Failed',
            );
        }
    }

    public function cek_admin(){
        $data = User::where('role',1)->get();
        // dd($data);
        if(empty($data)){
            return ApiFormat::responseError(400,'Tidak Ada Data');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$data);
        }
    }

    public function edit_admin(Request $request, $id){
        try {
            $rules = [
                'name'                  => 'required|string|min:3|max:64',
                'email'                 => 'required|email|unique:users',
                'password'              => 'required|min:6|confirmed'
            ];
    
            $messages = [
                'name.required'         => 'Nama Lengkap wajib diisi',
                'name.min'              => 'Nama lengkap minimal 3 karakter',
                'name.max'              => 'Nama lengkap maksimal 64 karakter',
                'email.required'        => 'Email wajib diisi',
                'email.email'           => 'Email tidak valid',
                'email.unique'          => 'Email sudah terdaftar',
                'password.required'     => 'Password wajib diisi',
                'password.min'          => 'Password minimal 6 karakter',
                'password.confirmed'    => 'Password tidak sama dengan konfirmasi password'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
    
            if($validator->fails()){
                return response()->json([
                    'errors' => $validator->errors()->first(),
                ]);
            }

            $admin = User::whereId($id)->first();
            $admin->update([
                'name'              => $request->name,
                'email'             => strtolower($request->email),
                'password'          => Hash::make($request->password),
                'type'              => 1,
                'email_verified_at' => \Carbon\Carbon::now(),
            ]);

            $user = User::where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            $data = [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ];
            // return $this->sendResponse($data, 'Successfull Update Data Admin');
            return ApiFormat::kirimResponse(200,'Successfull Update Data Admin',$data);
        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Edit Data Admin Failed',
            );
        }
    }

    public function delete_admin($id){
        try {
            $admin = User::findOrFail($id);
            $data = $admin->delete();
            if($data){
                return ApiFormat::kirimResponse(200,'Data Admin Berhasil Di Hapus');
            }else{
                return ApiFormat::kirimResponse(400,'Data Admin Gagal DiHapus');
            }
        } catch (Exception $error) {
            return ApiFormat::kirimResponse(400,'Failed',$error);
        }
    }

    // pertanyaan cek token untuk setiap mao login nya gimana?
    public function verify_relawan($id,$tokens){ //id ==> id relawan / user role relawan
        // cek hak akses
        $hrd = User::where('tokens',$tokens)->first(); 
        // dd($hrd);
        if($hrd == null){
            return ApiFormat::responseError(404,'Anda Tidak Memiliki Hak');
        }else{
            //cek data user ada / tidak
            $relawan = User::where('id',$id)->first();
            if($relawan != null && $relawan->type == 0){
                //cek dan delete data user yang ada di dalam table verifikasi
                $data = Verifikasi::where('id_relawan',$relawan->id)->delete();
                
                // proses input ke table verifikasi baru dengan relawan yang sama
                $verify = Verifikasi::create([
                    'id_admin'          => $hrd->id,
                    'id_relawan'        => $id,
                    'status_verifikasi' => 1,
                ]);
    
                $sts = Verifikasi::where('id_relawan',$relawan->id)->first();
                $data = [
                    'token_hrd' => $tokens,
                    'token_type' => 'Bearer',
                    'user' => $relawan,
                    'status_verifikasi' => $sts->status_verifikasi,
                ];
                return $this->sendResponse($data, 'Berhasil di Approve');
                // return "Berhasil di Approve";
            }else{
                return ApiFormat::responseError(404,'Relawan gagal di approve');
                // return "Gagal Di Approve";
            }
        }

        // $d = auth()->user()->role;
        // // $d = Auth::check();
        // dd($d);
        // $relawan = User::where('role',0)->first();
        // if($relawan == null){
        //     return ApiFormat::responseError(404,'Data Relawan Tidak ada');
        // }else{
        //     Verifikasi::create([
        //         'id_admin'          => $hrd->id,
        //         'id_relawan'        => $user->id,
        //         'status_verifikasi' => 0,
        //     ]);
        // }
    }

    // buat hak akses untuk admin register
    public function create_register_admin($tokens){
        $hrd = User::where('tokens', $tokens)->first();
        // dd($hrd);
        if($hrd == null){
            return ApiFormat::responseError(404,'Token Tidak Di Temukan Atau HRD Tidak Login');
        }else{
            $tokenResult = $hrd->createToken('authToken')->plainTextToken;
            
            $tokenBaru = "akses{$tokenResult}";
            Sesi_User::create([
                'tokens' => $tokenBaru,
            ]);
            $data = [
                'access_token_register' => $tokenBaru,
                'user_make' => $hrd,
                'link_register_admin' => "http://127.0.0.1:8000/api/register/admin/{$tokenBaru}"
            ];

            
            return $this->sendResponse($data, 'Akses Link Register Berhasil Di Buat');
        }
    }

    public function delete_akses_registrasi($token){
        $cekToken = Sesi_User::where('tokens',$token)->first();
        if($cekToken == null){
            return ApiFormat::responseError(404,'Token Tidak Valid');
        }else{
            $cekToken->delete();
            return ApiFormat::kirimResponse(200,"Token Akses Berhasil Di Hapus");
        }
    }
}
