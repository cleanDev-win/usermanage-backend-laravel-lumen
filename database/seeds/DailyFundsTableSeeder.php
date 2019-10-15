<?php

use Illuminate\Database\Seeder;

class DailyFundsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $price = 0.6323;
        $time  = date( 'Y-m-d H:i:s' );
        $time  = date( 'Y-m-d H:i:s', strtotime( $time . ' -365 days' ) );
        for ( $k = 1; $k <= 364; $k++ )
        {
            for ( $i = 1; $i <= 11; $i++ )
            {
                $price = $price + 0.3;
                DB::table( 'daily_funds' )->insert( [
                    'fund_id' => $i,
                    'price'   => $this->randomPrice(),
                    'as_at'   => $time,
                    'status'  => 1,
                ] );
            }
            $time = date( 'Y-m-d H:i:s', strtotime( $time . ' +1 days' ) );
        }
    }

    private function randomPrice()
    {
        $st_num  = 1;
        $end_num = 3;
        $mul     = 1000000;
        $num     = mt_rand( $st_num * $mul, $end_num * $mul ) / $mul;

        return round( $num, 4 );
    }
}
