<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProviderToDtesTable extends Migration
{
    public function up()
    {
        Schema::table('dtes', function (Blueprint $table) {
            $table->string('provider', 20)->default('rrd')->after('tipoDte');
            $table->string('emisor_nit', 20)->nullable()->after('provider');
        });
    }

    public function down()
    {
        Schema::table('dtes', function (Blueprint $table) {
            $table->dropColumn(['provider', 'emisor_nit']);
        });
    }
}
