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
        Schema::create('nexus', function (Blueprint $table) {
            $table->id('idnexus');
            $table->string('network_point');
            $table->decimal('cost', 8, 2);
            $table->integer('idproject')->unsigned();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();

            $table->foreign('idproject')->references('idproject')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nexus');
    }
};
