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
        Schema::create('servers', function (Blueprint $table) {
            $table->id('idserver');
            $table->string('name')->unique();
            $table->string('active')->unique();
            $table->string('machine_name');
            $table->string('hostname');
            $table->string('service');
            $table->integer('idproject')->unsigned();
            $table->integer('idsow')->unsigned()->nullable(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->foreign('idproject')->references('idproject')->on('projects')->onUpdate('cascade');
            $table->foreign('idsow')->references('idsow')->on('sows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
    }
};
