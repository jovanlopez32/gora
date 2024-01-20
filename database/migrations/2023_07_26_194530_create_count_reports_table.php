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
        Schema::create('count_reports', function (Blueprint $table) {
            $table->id();

            $table->string('technical_name');

            #Relacion de la etiqueta qr con la tabla de las etiquetas.
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('set null');

            #Relacion de la etiqueta qr con la tabla de los dispositivos.
            $table->unsignedBigInteger('device_id')->nullable();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('set null');

            #Relacion de la etiqueta con la tabla de los sectores.
            $table->unsignedBigInteger('sector_id')->nullable();
            $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('set null');

            $table->double('previous_reading')->nullable();
            $table->double('current_reading')->nullable();

            $table->double('previous_reading_print')->nullable();
            $table->double('current_reading_print')->nullable();

            $table->double('previous_reading_scan')->nullable();
            $table->double('current_reading_scan')->nullable();

            $table->double('production');
            $table->double('production_print');
            $table->double('production_scan');

            $table->double('total');
            $table->double('total_print');
            $table->double('total_scan');


            $table->double('total_global');


            $table->string('url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('count_reports');
    }
};
