<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class DailyFunds extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'fund_id',
        'price',
        'as_at',
    ];

    /**
     * @param $funds
     */
    private static function formatFunds( $funds )
    {
        $data = [];
        if ( empty( $funds ) )
        {
            return $data;
        }

        if ( !empty( $funds ) )
        {
            foreach ( $funds as $fund )
            {
                $data[] = [
                    'id'      => $fund->id,
                    'fund_id' => $fund->fund_id,
                    'name'    => Funds::getFundName( $fund->fund_id ),
                    'price'   => $fund->price,
                    'as_at'   => $fund->as_at != '' ? date( 'Y-m-d', strtotime( $fund->as_at ) ) : '',
                    'status'  => $fund->status == 1 ? 'Approved' : 'Pending',
                ];
            }
        }

        return $data;
    }

    /**
     * Returns latest daily funds
     *
     * @return array
     */
    public static function getDailyFunds()
    {
        $last_date = static::query()->orderBy( 'id', 'DESC' )->first()->as_at;
        $funds     = static::query()->where( 'as_at', 'like', '%' . date( 'Y-m-d', strtotime( $last_date ) ) . '%' )->get();
        $data      = [
            'date'  => $last_date,
            'funds' => [],
        ];

        $data['funds'] = self::formatFunds( $funds );

        return $data;
    }

    /**
     * Returns all daily funds
     *
     * @return array
     */
    public static function getAllDailyFunds( $offset = 0, $per_page = 15, $fund_id = '', $date = '' )
    {
        $data       = [];
        $countQuery = static::query();
        $query      = static::query();
        if ( $fund_id != '' && $fund_id != null )
        {
            $countQuery = $countQuery->where( 'fund_id', $fund_id );
            $query      = $query->where( 'fund_id', $fund_id );
        }
        if ( $date != '' && $date != null )
        {
            $countQuery = $countQuery->whereDate( 'as_at', '=', $date );
            $query      = $query->whereDate( 'as_at', '=', $date );
        }
        $query = $query->skip( $offset * $per_page )->orderBy( 'id', 'DESC' )->take( $per_page )->get();

        $data['total'] = $countQuery->count();
        $data['items'] = static::formatFunds( $query );

        return $data;
    }

    /**
     * Returns Current As At Value
     *
     * @param int $id
     */
    public static function getCurrentAsAt( $id )
    {
        return static::query()->where( 'fund_id', $id )->orderBy( 'id', 'DESC' )->first()->as_at;
    }

    /**
     * Returns min date for datepicker
     *
     * @param int $id
     */
    public static function getMinDate( $id )
    {
        return static::query()->where( 'fund_id', $id )->orderBy( 'id', 'ASC' )->first()->as_at;
    }

    /**
     * Returns max date for datepicker
     *
     * @param int $id
     */
    public static function getMaxDate( $id )
    {
        return static::query()->where( 'fund_id', $id )->orderBy( 'id', 'DESC' )->first()->as_at;
    }

    /**
     * @param $id
     */
    public static function getStartPrice( $id )
    {
        return static::query()->where( 'fund_id', $id )->orderBy( 'id', 'ASC' )->first()->price;
    }

    /**
     * @param $id
     */
    public static function getCurrentPrice( $id )
    {
        return static::query()->where( 'fund_id', $id )->orderBy( 'id', 'DESC' )->first()->price;
    }

    /**
     * @param $data
     * @param $date
     */
    public static function validateBeforeInsert( $data )
    {
        $date = '%' . $data[0]['as_at'] . '%';
        //$date  = '%2019-08-26%';
        $ids = Arr::pluck( $data, 'fund_id' );

        $query = static::query()->where( 'as_at', 'LIKE', $date )->whereIn( 'fund_id', $ids );

        //$sql = str_replace_array( '?', $query->getBindings(), $query->toSql() );
        //return $sql;

        return $query->get()->isEmpty();
    }

    /**
     * @param $data
     */
    public static function importData( $data )
    {
        return static::insert( $data );
    }

    /**
     * @param $fund_id
     */
    public static function getChartData( $id, $from = '', $to = '' )
    {
        $query = static::query()->where( 'fund_id', $id );
        if ( $from != '' )
        {
            $query = $query->whereDate( 'as_at', '>=', date( 'Y-m-d', strtotime( $from ) ) );
        }
        if ( $to != '' )
        {
            $query = $query->whereDate( 'as_at', '<=', date( 'Y-m-d', strtotime( $to ) ) );
        }
        $query = $query->orderBy( 'as_at', 'ASC' )->get();

        return $query;
    }
}