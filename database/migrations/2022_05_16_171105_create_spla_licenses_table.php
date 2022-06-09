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
            $table->string('code');
            $table->string('name');
            $table->decimal('cost', 8, 2);
            $table->string('type');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
            $table->unique(['code', 'type']);
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
