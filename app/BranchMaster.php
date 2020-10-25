<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchMaster extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'branch_master';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id', 'branch_code', 'region_id','created_at','updated_at'
    ];

    /**
     * Get the post that owns the comment.
     */
    public function region()
    {
        return $this->belongsTo(RegionMaster::class,'region_id','id');
    }

      /**
     * Get the post that owns the comment.
     */
    public function users()
    {
        return $this->hasMany(User::class,'group4','id');
    }

    public function scopeBranchData($query){
        $user = Auth::user();
        
        if(isset($user->group2)  && $user->group2 !== 0 ){
            $query = $query->where("region_id",$user->group2);
        }
        if(isset($user->group1)  && $user->group1 !== 0 ){
            $query = $query->where("id",$user->group1);
        }
         return $query;
    }
}
