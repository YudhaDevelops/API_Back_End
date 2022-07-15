<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('nama_lengkap', 100);
            $table->string('email', 255);
            $table->string('password', 255);
            $table->integer('role');
            $table->timestamps();
        });

        Schema::create('session_user', function (Blueprint $table) {
            $table->id('id');
            $table->string('sesi');
            $table->timestamps();
        });

        Schema::create('verifikasi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_admin');
            $table->unsignedBigInteger('id_relawan');
            $table->integer('status_verifikasi');

            $table->foreign('id_admin') -> references('id') -> on('users');
            $table->foreign('id_relawan')-> references('id') -> on('users');
            $table->timestamps();
            $table->primary(['id_admin', 'id_relawan']);
        });

        Schema::create('provinsi', function (Blueprint $table) {
            $table->char('id',2);
            $table->string('nama',255);

            $table->primary('id');
        });

        Schema::create('kabupaten', function (Blueprint $table) {
            $table->char('id',4);
            $table->char('id_provinsi',2);
            $table->string('nama',255);

            $table->primary('id');
            $table->foreign('id_provinsi')->references('id')->on('provinsi');
        });

        Schema::create('kecamatan', function (Blueprint $table) {
            $table->char('id',7);
            $table->char('id_kabupaten',4);
            $table->string('nama',255);

            $table->primary('id');
            $table->foreign('id_kabupaten')->references('id')->on('kabupaten');
        });

        Schema::create('kelurahan', function (Blueprint $table) {
            $table->char('id',10);
            $table->char('id_kecamatan',7);
            $table->string('nama',255);

            $table->primary('id');
            $table->foreign('id_kecamatan')->references('id')->on('kecamatan');
        });

        Schema::create('alamat', function (Blueprint $table) {
            $table->id();
            $table->text('detail_alamat');
            $table->char('id_kelurahan', 10);
            $table->char('kode_pos', 5);
            $table->timestamps();
            $table->foreign('id_kelurahan') -> references('id') -> on('kelurahan');
        });

        Schema::create('data_diri', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('id_user');
            $table->enum('jenis_kelamin',['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir',50);
            $table->date('tanggal_lahir');
            $table->string('no_telepon',20);
            $table->text('alamat_ktp');
            $table->unsignedBigInteger('id_alamat_ktp');
            $table->text('alamat_domisili');
            $table->unsignedBigInteger('id_alamat_domisili');
            $table->enum('gol_darah',['A', 'B', 'AB', 'O']);
            $table->text('kondisi_disabilitas');
            $table->string('penyakit',255);
            $table->enum('status_vaksin', ['Belum','Dosis 1','Dosis 2','Booster']);
            $table->boolean('pernah_bekerja_sama');
            $table->boolean('bpjs_kesehatan');
            $table->boolean('bpjs_ketenagakerjaan');
            $table->string('instansi', 255);
            // $table->string('file_pelatihan', 255);
            $table->boolean('ready')->default('0');
            $table->timestamps();
            $table->foreign('id_user') -> references('id') -> on('users');
            $table->foreign('id_alamat_ktp') -> references('id') -> on('alamat');
            $table->foreign('id_alamat_domisili') -> references('id') -> on('alamat');

            $table->primary('id_user');
        });

        Schema::create('keahlian', function (Blueprint $table) {
            $table->id('id_keahlian');
            $table->string('jenis_keahlian',25);
            $table->timestamps();
        });

        Schema::create('relawan_keahlian', function (Blueprint $table) {
            $table->unsignedBigInteger('id_relawan');
            $table->unsignedBigInteger('id_keahlian');
            $table->timestamps();
            $table->foreign('id_relawan')->references('id')->on('users');
            $table->foreign('id_keahlian')->references('id_keahlian')->on('keahlian');
            $table->primary(['id_relawan', 'id_keahlian']);
        });

        Schema::create('pengalaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi',50);
            $table->string('durasi_waktu',20);
            $table->timestamps();
        });

        Schema::create('relawan_pengalaman', function (Blueprint $table) {
            $table->unsignedBigInteger('id_relawan');
            $table->unsignedBigInteger('id_pengalaman');
            $table->timestamps();
            $table->foreign('id_relawan')->references('id')->on('users');
            $table->foreign('id_pengalaman')->references('id')->on('pengalaman');

            $table->primary(['id_relawan', 'id_pengalaman']);
        });

        Schema::create('project', function (Blueprint $table) {
            $table->id('id_project');
            $table->string('judul',255);
            $table->text('deskripsi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('lokasi');
        });

        Schema::create('relawan_project', function (Blueprint $table) {
            $table->unsignedBigInteger('id_relawan');
            $table->unsignedBigInteger('id_project');
            $table->timestamps();
            $table->foreign('id_relawan')->references('id')->on('users');
            $table->foreign('id_project')->references('id_project')->on('project');

            $table->primary(['id_relawan', 'id_project']);
        });

        

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('session_user')) {
            Schema::drop('session_user');
        }
        if (Schema::hasTable('verifikasi')) {
            Schema::drop('verifikasi');
        }
        if (Schema::hasTable('data_diri')) {
            Schema::drop('data_diri');
        }
        if (Schema::hasTable('relawan_keahlian')) {
            Schema::drop('relawan_keahlian');
        }
        if (Schema::hasTable('relawan_pengalaman')) {
            Schema::drop('relawan_pengalaman');
        }
        if (Schema::hasTable('relawan_project')) {
            Schema::drop('relawan_project');
        }
        if (Schema::hasTable('keahlian')) {
            Schema::drop('keahlian');
        }
        if (Schema::hasTable('pengalaman')) {
            Schema::drop('pengalaman');
        }
        if (Schema::hasTable('project')) {
            Schema::drop('project');
        }
        if (Schema::hasTable('users')) {
            Schema::drop('users');
        }
        if (Schema::hasTable('alamat')) {
            Schema::drop('alamat');
        }
        if (Schema::hasTable('kelurahan')) {
            Schema::drop('kelurahan');
        }
        if (Schema::hasTable('kecamatan')) {
            Schema::drop('kecamatan');
        }
        if (Schema::hasTable('kabupaten')) {
            Schema::drop('kabupaten');
        }
        if (Schema::hasTable('provinsi')) {
            Schema::drop('provinsi');
        }
    }
};
