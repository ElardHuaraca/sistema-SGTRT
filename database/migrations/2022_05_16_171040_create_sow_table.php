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
            $table->string('type');
            $table->decimal('cost_cpu', 8, 2);
            $table->decimal('cost_ram', 8, 2);
            $table->decimal('cost_hdd_mechanical', 8, 2);
            $table->decimal('cost_hdd_solid', 8, 2);
            $table->decimal('cost_mo_clo_sw_ge', 8, 2);
            $table->decimal('cost_mo_cot', 8, 2);
            $table->decimal('cost_cot_monitoring', 8, 2);
            $table->decimal('cost_license_vssp', 8, 2);
            $table->decimal('cost_license_vssp_srm', 8, 2);
            $table->decimal('cost_link', 8, 2);
            $table->decimal('add_cost_antivirus', 8, 2);
            $table->decimal('add_cost_win_license_cpu', 8, 2);
            $table->decimal('add_cost_win_license_ram', 8, 2);
            $table->decimal('add_cost_linux_license', 8, 2);
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
