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
        Schema::create('spla_assigned_discounts', function (Blueprint $table) {
            $table->id('iddiscount');
            $table->decimal('percentage', 8, 2);
            $table->integer('idserver')->unsigned();
            $table->integer('idspla')->unsigned();
            $table->timestamps();

            $table->foreign('idserver')->references('idserver')->on('servers')->onUpdate('cascade');
            $table->foreign('idspla')->references('idspla')->on('spla_licenses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spla_assigned_discounts');
    }
};
