<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchMaster extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'branch_masters';

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

}
