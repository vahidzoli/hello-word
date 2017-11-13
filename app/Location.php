<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Illuminate\Support\Facades\DB;
use Phaza\LaravelPostgis\Geometries\Point;

class Location extends Model
{
    use PostgisTrait;

    protected $table = 'locations';

    protected $fillable = [
        'name',
        'polygon'
    ];

    protected $postgisFields = [
        'polygon'
    ];

    public static function pointInPolygon($lat,$lng)
    {
        $point = new Point($lng,$lat);

        $temp = DB::select("
            SELECT id,name                  
            FROM locations
            WHERE ST_Within(ST_GeomFromText('POINT('||?||')',27700), polygon)",[$point]
        );

        return $temp;

    }

}
