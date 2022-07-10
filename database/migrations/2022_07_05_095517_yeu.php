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
        });

        Schema::create('verifikasi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_admin');
            $table->unsignedBigInteger('id_relawan');
            $table->integer('status_verifikasi');

            $table->foreign('id_admin') -> references('id') -> on('users');
            $table->foreign('id_relawan')-> references('id') -> on('users');

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

        Schema::create('data_diri', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('id_user');
            $table->enum('jenis_kelamin',['Laki-laki', 'Perempuan']);
            $table->date('tanggal_lahir');
            $table->string('no_telepon',20);
            $table->char('alamat_ktp', 10);
            $table->string('kode_pos_ktp', 5);
            $table->char('alamat_domisili', 10);
            $table->string('kode_pos_domisili', 5);
            $table->enum('gol_darah',['A', 'B', 'AB', 'O']);
            $table->text('kondisi_disabilitas');
            $table->string('penyakit',255);
            $table->enum('status_vaksin', ['Belum','Dosis 1','Dosis 2','Booster']);
            $table->boolean('pernah_bekerja_sama');
            $table->boolean('bpjs_kesehatan');
            $table->boolean('bpjs_ketenagakerjaan');
            $table->string('instansi', 255);
            $table->string('file_pelatihan', 255);
            $table->boolean('ready')->default('0');

            $table->foreign('id_user') -> references('id') -> on('users');
            $table->foreign('alamat_ktp') -> references('id') -> on('kelurahan');
            $table->foreign('alamat_domisili') -> references('id') -> on('kelurahan');

            $table->primary('id_user');
        });

        Schema::create('keahlian', function (Blueprint $table) {
            $table->id('id_keahlian');
            $table->string('jenis_keahlian',25);
        });

        Schema::create('relawan_keahlian', function (Blueprint $table) {
            $table->unsignedBigInteger('id_relawan');
            $table->unsignedBigInteger('id_keahlian');

            $table->foreign('id_relawan')->references('id')->on('users');
            $table->foreign('id_keahlian')->references('id_keahlian')->on('keahlian');
        });

        Schema::create('pengalaman', function (Blueprint $table) {
            $table->id('id_pengalaman');
            $table->string('nama_instansi',50);
            $table->string('durasi_waktu',20);
        });

        Schema::create('relawan_pengalaman', function (Blueprint $table) {
            $table->unsignedBigInteger('id_relawan');
            $table->unsignedBigInteger('id_pengalaman');

            $table->foreign('id_relawan')->references('id')->on('users');
            $table->foreign('id_pengalaman')->references('id_pengalaman')->on('pengalaman');
        });

        Schema::create('project', function (Blueprint $table) {
            $table->id('id_project');
            $table->string('nama_project',50);
            $table->string('foto',255);
        });

        Schema::create('relawan_project', function (Blueprint $table) {
            $table->unsignedBigInteger('id_relawan');
            $table->unsignedBigInteger('id_project');

            $table->foreign('id_relawan')->references('id')->on('users');
            $table->foreign('id_project')->references('id_project')->on('project');
        });

        

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
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
