<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MegaZoneMaster extends Model
{
            /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mega_zone_masters';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mega_zone_id', 'mega_zone_name','created_at','updated_at'
    ];
}
