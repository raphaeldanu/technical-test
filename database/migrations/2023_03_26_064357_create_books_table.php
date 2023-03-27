<?php

use App\Models\Genre;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Genre::class)->constrained();
            $table->string('kode_buku')->unique();
            $table->string('judul_buku');
            $table->string('penulis');
            $table->string('penerbit');
            $table->integer('tahun_terbit');
            $table->text('sinopsis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
