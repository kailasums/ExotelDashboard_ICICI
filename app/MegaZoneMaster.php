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

    protected $primaryKey = 'mega_zone_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mega_zone_id', 'mega_zone_name','created_at','updated_at'
    ];

    /**
     * Get the comments for the blog post.
     */
    public function zones()
    {
        return $this->hasMany(ZoneMaster::class,'mega_zone_id','mega_zone_id');
    }

         /**
     * Get the post that owns the comment.
     */
    public function users()
    {
        return $this->hasMany(User::class,'group1','mega_zone_id');
    }
}
