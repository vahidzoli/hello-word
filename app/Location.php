<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

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

//    protected $postgisTypes = [
//        'polygon' => [
//            'geomtype' => 'geometry',
//            'srid' => 27700
//        ]
//    ];
}
