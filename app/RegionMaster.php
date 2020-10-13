<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegionMaster extends Model
{
    use SoftDeletes;
          /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'region_master';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'region_name', 'zone_id','created_at','updated_at'
    ];

         /**
     * Get the post that owns the comment.
     */
    public function zone()
    {
        return $this->belongsTo(ZoneMaster::class,'zone_id','id');
    }

       /**
     * Get the comments for the blog post.
     */
    public function branchs()
    {
        return $this->hasMany(BranchMaster::class,'region_id','id');
    }

             /**
     * Get the post that owns the comment.
     */
    public function users()
    {
        return $this->hasMany(User::class,'group3','id');
    }

    public function scopeRegoinData($query){
        $user = Auth::user();
        $query = $query->where("megazone_id",$user->group4);
        if(isset($user->group3)  && $user->group3 !== 0 ){
            $query = $query->where("zone_id",$user->group3);
        }
        if(isset($user->group2)  && $user->group2 !== 0 ){
            $query = $query->where("id",$user->group2);
        }
         return $query;
    }

}
