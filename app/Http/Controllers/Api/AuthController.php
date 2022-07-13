<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sesi_User;
use App\Models\Verifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiFormat;
use Session;
use AuthenticatesUsers;
// use Illuminate\Auth\RequestGuard;

class AuthController extends Controller
{
    
    public function register_relawan(Request $request){
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
                // return ApiFormat::responseError(400,'Something went wrong',[
                //     'error' => $validator
                // ]);
                // return ApiFormat::kirimResponse(400,'Registrasi Gagal');
                // return redirect()->back()->withErrors($validator)->withInput($request->all);
            }
            
            User::create([
                'name' => $request->name,
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'role'  => 0,
            ]);

            // cek dara hrd / akun hrd
            $user = User::where('email', $request->email)->first();
            $hrd = User::where('role',2)->first();
            // dd($hrd);
            if($hrd == null){
                return ApiFormat::responseError(500,'Data HRD Tidak ada');
            }else{
                Verifikasi::create([
                    'id_admin'          => $hrd->id,
                    'id_relawan'        => $user->id,
                    'status_verifikasi' => 0,
                ]);
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            $sts = Verifikasi::where('id_relawan',$user->id)->first();
            $data = [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
                'status_verifikasi' => $sts->status_verifikasi,
            ];
            return $this->sendResponse($data, 'Successfull Register');
        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Registration Failed',
            );
        }
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|max:255|unique:users',
        //     'password' => 'required|string|min:8'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors());
        // }

        // $user = User::create([
        //     'name'      => $request->name,
        //     'email'             => $request->email,
        //     'password'          => Hash::make($request->password),
        //     'role'              => 0,
        // ]);

        // $token = $user->createToken('auth_token')->plainTextToken;

        // return response()->json([
        //     'data' => $user,
        //     'access_token' => $token,
        //     'token_type' => 'Bearer'
        // ]);
    }

    // edit bagian hrd
    public function register_admin(Request $request,$token){
        $cekToken = Sesi_User::where('tokens',$token)->first();
        if($cekToken != null){
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
                return $this->sendResponse($data, 'Successfull Register');
            } catch (Exception $error) {
                return $this->sendError(
                    [
                        'message' => 'Something went wrong',
                        'error' => $error
                    ],
                    'Registration Failed',
                );
            }
        }else{
            return ApiFormat::responseError(404,'Token Tidak Valid Atau Anda Tidak Memiliki Akses Untuk Melakukan Registrasi');
        }
    }

    public function register_hrd(Request $request){
        $hrd = User::where('role', 2)->first();
        if($hrd == null){
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
                    'role'  => 2,
                ]);
    
                $user = User::where('email', $request->email)->first();
                $tokenResult = $user->createToken('authToken')->plainTextToken;
    
                $data = [
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $user
                ];
                return $this->sendResponse($data, 'Successfull Register');
            } catch (Exception $error) {
                return $this->sendError(
                    [
                        'message' => 'Something went wrong',
                        'error' => $error
                    ],
                    'Registration Failed',
                );
            }
        }else{
            return ApiFormat::responseError(500,'Acount HRD Sudah Ada');
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return $this->sendError('Unauthorized', 'Authentication Failed', 500);
            }

            //Jika hash tidak sesuai
            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            //jika berhasil maka login
            $tokenResult = $user->createToken( str()->random(40) )->plainTextToken;

            //input token table user
            // $user->update([
            //     'tokens' => $tokenResult,
            // ]);

            // input token table sesion user
            // Sesi_User::create([
            //     'tokens' => $tokenResult,
            // ]);
           
            return $this->sendResponse([
                'access_token'  => $tokenResult,
                'token_type'    => 'Bearer',
                'user'          => $user
            ], 'Authenticated');

        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Login Failed',
            );
        }
        // if (! Auth::attempt($request->only('email', 'password'))) {
        //     return response()->json([
        //         'message' => 'Unauthorized'
        //     ], 401);
        // }

        // $user = User::where('email', $request->email)->firstOrFail();

        // $token = $user->createToken('auth_token')->plainTextToken;

        // $user->update([
        //     'tokens'          => $token,
        // ]);

        // $makeSesi = Sesi_User::create([
        //     'tokens'        => $token,
        // ]);

        // $admin->update([
        //     'email'             => strtolower($request->email),
        //     'nama_lengkap'      => $request->nama_lengkap,
        //     'password'          => Hash::make($request->password),
        //     'type'              => 1,
        //     'email_verified_at' => \Carbon\Carbon::now(),
        // ]);

        // return response()->json([
        //     'message' => 'Login success',
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        //     'status'    => 201,
        //     'data'      => $user
        // ],201);

    }

    public function logout(Request $request)
    {
        // dd($request);
        // $request->logout();
        // Auth::logout();
        // $this->guard()->logout();
        
        // dd(auth()->user());
        // dd(Auth::logout());
        // dd(Session::flush());
        // dd(Auth::guard($guard)->logout());
        Auth::user()->tokens()->delete();
        // $request->user()->currentAccessToken()->delete();

        // Auth::user()->logout();
        $request->session()->invalidate();
        // $request->session()->regeneratedToken();
        // dd(Auth::user());
        // $sesion = Sesi_User::where('tokens',$user->tokens)->firstOrFail();
        // // return response()->json([
        //     //     'data di session'   => $sesion->tokens,
        //     // ]);
        
            
        // if($user->tokens == $sesion->tokens){
        //     Auth::user()->update([
        //         'tokens'          => null,
        //     ]);
            
        //     Auth::user()->tokens()->delete();
            // $sesion->delete();
        return ApiFormat::kirimResponse(200,"Berhasil Logout");
        // }else{
        //     return $this->sendError(
        //         [
        //             'message' => 'Something went wrong',
        //             'error' => $error
        //         ],
        //         'Logout Failed',
        //     );
        // }
    }

    public function cekUser($id){
        $user = User::where('id', $id)->first();
        if($user == null){
            return ApiFormat::responseError(400,'User Tidak Ditemukan');
        }else{
            return $this->sendResponse($user, 'User Ditemukan');
        }
    }

    public function dataSemuaUser(){
        $user = User::all();
        if($user == null){
            return ApiFormat::responseError(400,'Tidak Ada Data');
        }else{
            return ApiFormat::kirimResponse(200,'Data Ditemukan',$user);
        }
    }
}
