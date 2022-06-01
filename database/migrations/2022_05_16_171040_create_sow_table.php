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
        Schema::create('sows', function (Blueprint $table) {
            $table->id('idsow');
            $table->string('version');
            $table->string('name');
            $table->decimal('cost_cpu', 8, 2)->nullable();
            $table->decimal('cost_ram', 8, 2);
            $table->decimal('cost_hdd_mechanical', 8, 2);
            $table->decimal('cost_hdd_ssd', 8, 2);
            $table->decimal('cost_mo_clo_sw_ge', 8, 2);
            $table->decimal('cost_mo_cot', 8, 2);
            $table->decimal('cost_cot_monitoring', 8, 2);
            $table->decimal('cost_license_vssp', 8, 2);
            $table->decimal('cost_link', 8, 2);
            $table->decimal('add_cost_antivirus', 8, 2);
            $table->decimal('add_cost_win_license', 8, 2);
            $table->decimal('add_cost_cpu', 8, 2);
            $table->decimal('add_cost_linx_license', 8, 2);
            $table->decimal('cost_backup_db', 8, 2);
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
        Schema::dropIfExists('sow');
    }
};
