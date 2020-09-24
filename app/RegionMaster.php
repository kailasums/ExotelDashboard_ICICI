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

         /**
     * Get the post that owns the comment.
     */
    public function zone()
    {
        return $this->belongsTo(ZoneMaster::class,'zone_id','zone_id');
    }

       /**
     * Get the comments for the blog post.
     */
    public function branchs()
    {
        return $this->hasMany(BranchMaster::class,'region_id','region_id');
    }

             /**
     * Get the post that owns the comment.
     */
    public function users()
    {
        return $this->hasMany(User::class,'group3','region_id');
    }
}
