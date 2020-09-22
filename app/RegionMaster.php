<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegionMaster extends Model
{
          /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'region_masters';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'region_id', 'region_name', 'zone_id','created_at','updated_at'
    ];
}
