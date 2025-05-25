<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update existing transactions
        DB::table('transactions')
            ->whereNotNull('income')
            ->update(['type' => 'income']);

        DB::table('transactions')
            ->whereNotNull('outcome')
            ->update(['type' => 'outcome']);
    }

    public function down()
    {
        // No need to revert data updates
    }
}; 