<?php

use App\Models\EndDay;
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
        Schema::create('end_days', function (Blueprint $table) {
            $table->id();
            $table->Integer('day');
            $table->timestamps();
        });

        EndDay::create([
            'day' => 15
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('end_days');
    }
};
