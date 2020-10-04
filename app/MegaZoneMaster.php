<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MegaZoneMaster extends Model
{
    use SoftDeletes;
            /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'megazone_masters';

    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'megazone_name','created_at','updated_at'
    ];

    /**
     * Get the comments for the blog post.
     */
    public function zones()
    {
        return $this->hasMany(ZoneMaster::class,'megazone_id','id');
    }

         /**
     * Get the post that owns the comment.
     */
    public function users()
    {
        return $this->hasMany(User::class,'group1','id');
    }
}
