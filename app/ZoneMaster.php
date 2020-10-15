<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ZoneMaster extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'zone_master';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'zone_name', 'megazone_id','created_at','updated_at'
    ];

      /**
     * Get the post that owns the comment.
     */
    public function megaZone()
    {
        return $this->belongsTo(MegaZoneMaster::class,'megazone_id','id');
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
    
    public function scopeZoneData($query){
        $user = Auth::user();
        $query = $query->where("megazone_id",$user->group4);
        if(isset($user->group3)  && $user->group3 !== 0 ){
            $query = $query->where("id",$user->group3);
        }
         return $query;
    }
}
