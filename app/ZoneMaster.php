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
        'id', 'zone_name', 'mega_zone_id','created_at','updated_at'
    ];

      /**
     * Get the post that owns the comment.
     */
    public function megaZone()
    {
        return $this->belongsTo(MegaZoneMaster::class,'mega_zone_id','id');
    }

      /**
     * Get the comments for the blog post.
     */
    public function regions()
    {
        return $this->hasMany(RegionMaster::class,'zone_id','id');
    }
           /**
     * Get the post that owns the comment.
     */
    public function users()
    {
        return $this->hasMany(User::class,'group2','id');
    }
    
}
