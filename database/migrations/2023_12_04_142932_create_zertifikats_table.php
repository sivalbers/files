<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zertifikats', function (Blueprint $table) {
            $table->id();
            $table->string('ident');
            $table->string('teilenr')->nullable();
            $table->string('bestellnr');
            $table->string('bezeichnung_id')->nullable();
            $table->string('werkstoff')->nullable();
            $table->integer('herstellungsjahr')->nullable();
            $table->string('dn1')->nullable();
            $table->string('dn2')->nullable();
            $table->string('werk_id')->nullable();
            $table->string('druckstufe_id')->nullable();
            $table->integer('rohrabmessung')->nullable();
            $table->string('materialnummer')->nullable();
            $table->string('zaehlergroesse')->nullable();
            $table->string('zaehlerart_id')->nullable();
            $table->string('filename')->nullable();
            $table->string('filehash')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zertifikats');
    }
};
