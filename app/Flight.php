<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $table = 'flights';

    protected $primaryKey = 'id';

    protected $fillable = [
        
        'code',
        'lon',
        'lat' 
    ];

    public static function getData()
    {
        $value=DB::table('flights')->get();
        return $value;
    }
}
