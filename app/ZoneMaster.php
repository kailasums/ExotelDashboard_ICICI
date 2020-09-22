<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZoneMaster extends Model
{
           /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'zone_masters';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'zone_id', 'zone_name', 'mega_zone_id','created_at','updated_at'
    ];
}
