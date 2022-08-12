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
        Schema::create('assign_services', function (Blueprint $table) {
            $table->id('idasser');
            $table->boolean('is_backup')->default(false);
            $table->boolean('is_additional')->default(false);
            $table->boolean('is_windows_license')->default(false);
            $table->boolean('is_antivirus')->default(false);
            $table->boolean('is_linux_license')->default(false);
            $table->boolean('is_additional_spla')->default(false);
            $table->integer('idserver')->unsigned();
            $table->timestamps();

            $table->foreign('idserver')->references('idserver')->on('servers')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assign_services');
    }
};
