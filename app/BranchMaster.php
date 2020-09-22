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
        'branch_id', 'branch_code', 'religion_id','created_at','updated_at'
    ];
}
