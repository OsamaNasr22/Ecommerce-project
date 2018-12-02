<?php

use Illuminate\Database\Seeder;

class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        \App\Coupon::create([
            'code'=>'abc123',
            'type'=>'fixed',
            'value'=>2000
        ]);
        \App\Coupon::create([
            'code'=>'dfg123',
            'type'=>'percent',
            'percent_of'=>50
        ]);
    }
}
