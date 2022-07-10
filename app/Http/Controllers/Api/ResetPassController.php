<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiFormat;

class ResetPassController extends Controller
{
    // public function getEmail()
    // {
    //     $judul="Sending Email || Admin Skuy-Replay";
    //     return view('admin.email',compact('judul'));
    // }
    
    public function kirimEmail(Request $request){
        try {
            $rules = [
                'email'                 => 'required|email|exists:users',
            ];
    
            $messages = [
                'email.required'        => 'Email wajib diisi',
                'email.email'           => 'Email tidak valid',
                'email.exists'          => 'Email tidak terdaftar'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if($validator->fails()){
                return response()->json([
                    'errors' => $validator->errors()->first(),
                ]);
            }

            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email, 
                'token' => $token, 
                'created_at' => Carbon::now()
            ]);

            Mail::send('Auth.verify', ['token' => $token], function($message) use($request){
                $message->to($request->email);
                $message->subject('Reset Password Notification');
            });

            $data = DB::table('password_resets')->where(['token'=> $token])->get();
            return ApiFormat::kirimResponse(200,'We have e-mailed your password reset link!',$data);

        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Sending Email error',
            );
        }
    }

    // public function getPass($token) 
    // {
    //     $judul = "Reset Password Admin || Admin Skuy-Replay";
    //     return view('admin.reset',compact('judul'), ['token' => $token]);
    // }
    
    public function updatePass(Request $request){
        try {
            $rules = [
                'email'                 => 'required|email',
                'password'              => 'required|min:6|confirmed',
                'password_confirmation' => 'required',
            ];
    
            $messages = [
                'email.required'        => 'Email wajib diisi',
                'email.email'           => 'Email tidak valid',
                'password.required'     => 'Password wajib diisi',
                'password.min'          => 'Password minimal 6 karakter',
                'password.confirmed'    => 'Password tidak sama dengan konfirmasi password',
                'password_confirmation' => 'Password confirmation wajib diisi'
            ];
    
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if($validator->fails()){
                return response()->json([
                    'errors' => $validator->errors()->first(),
                ]);
            }
            
            $updatePassword = DB::table('password_resets')->where([
                'email' => $request->email, 
                'token' => $request->token
            ])->first();
            
            if(!$updatePassword){
                if($validator->fails()){
                    return response()->json([
                        'errors' => 'Invalid token!',
                    ]);
                }
                // return back()->withInput()->with('error', 'Invalid token!');
            }
            
            $user = User::where('email', $request->email)->update([
                'password' => Hash::make($request->password)
            ]);
            
            DB::table('password_resets')->where([
                'email'=> $request->email
            ])->delete();
            
            $data = User::where('email', $request->email)->first();
            return ApiFormat::kirimResponse(200,'Update Password Berhasil',$data);
            // return redirect('/login')->with('success', 'Your password has been changed!');
        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Update Password error',
            );
        }
    }
}
