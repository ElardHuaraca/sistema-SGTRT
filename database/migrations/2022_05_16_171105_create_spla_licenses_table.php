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
        Schema::create('spla_licenses', function (Blueprint $table) {
            $table->id('idspla');
            $table->string('code')->unique();
            $table->string('name');
            $table->decimal('cost', 8, 2)->nullable(false);
            $table->string('type')->nullable(false);
            $table->boolean('is_deleted')->default(false);
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
        Schema::dropIfExists('spla_licenses');
    }
};
