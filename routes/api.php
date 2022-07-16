<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ResetPassController;
use App\Http\Controllers\Api\DataDiriController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|   
    buat folder Helpers dulu di folder App trus buat file ApiFormat.php

    // buat controller
    php artisan make:controller  Api/MahasiswaController --resource

*/


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// belajar api mahasiswa
Route::get('/mahasiswa', [MahasiswaController::class,'index'])->middleware('auth:sanctum');
Route::post('/mahasiswa/store', [MahasiswaController::class,'store']);
Route::get('/mahasiswa/show/{id}',[MahasiswaController::class,'show']);
Route::post('/mahasiswa/update/{id}',[MahasiswaController::class,'update']);
Route::get('/mahasiswa/delete/{id}',[MahasiswaController::class,'destroy']);

Route::controller(AuthController::class)->group(function(){
    Route::get('/acount_user_data_application','dataSemuaUser');
    Route::post('/register','register_relawan');
    Route::post('/login','login');
    Route::post('/register/hrd','register_hrd');
    Route::post('/register/admin/{token}','register_admin');
    Route::get('/cekUser/{id}','cekUser');
});

// masalah lupa password
Route::controller(ResetPassController::class)->group(function () {
    Route::post('/kirimEmail','kirimEmail');
    Route::post('/updatePass','updatePass');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AdminController::class)->group(function () {
    Route::get('/pendaftaran','index');

    // route hrd
    Route::post('/hrd/add_admin','tambah_admin');
    Route::get('/hrd/cek_admin','cek_admin');
    Route::post('/hrd/edit_admin/{id}','edit_admin'); //id user yang role nya 1 atau admin
    Route::get('/hrd/delete_admin/{id}','delete_admin');//id user yang role nya 1 atau admin
    Route::get('/hrd/verify_relawan/{id}/{tokens}','verify_relawan');
    Route::get('/admin_akses_register/{tokens}','create_register_admin');
    Route::get('/hrd/delete_akses_registrasi/{token}','delete_akses_registrasi');
});

// route data diri
Route::controller(DataDiriController::class)->group(function(){
    Route::get('/getProvinsi','getProvinsi');
    Route::get('/getKabupaten','getKabupaten');
    Route::get('/getKecamatan','getKecamatan');
    Route::get('/getKelurahan','getKelurahan');

    // get berdasarkan id
    Route::get('/provinsi/{id}','provinsi');
    Route::get('/kabupaten/{id}','kabupaten');
    Route::get('/kecamatan/{id}','kecamatan');
    Route::get('/kelurahan/{id}','kelurahan');

    // isi data tabel alamat
    Route::post('/addAlamat','addAlamat');

    Route::get('/coba/{id}','coba');

    Route::get('/getAlamat','getAlamat');


    // soal data keahlian
    Route::post('/tambah_keahlian/{id}','tambah_keahlian');
    Route::post('/delete_keahlian/{id}','delete_keahlian');
    Route::post('/tambah_item_keahlian','tambah_item_keahlian');
    Route::get('/cekKeahlian','cekKeahlian');

    // soal data Pengalaman
    Route::post('/tambahPengalaman','tambahPengalaman');
    Route::get('/deletePengalaman','deletePengalaman');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class,'index']);
    Route::post('/mahasiswa/store', [MahasiswaController::class,'store']);
    // Route::get('/hrd/verify_relawan/{id}',[AdminController::class,'verify_relawan']);
    Route::post('/logout', [AuthController::class, 'logout']);
});