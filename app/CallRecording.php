<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallRecording extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'call_records';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fromNumber', 'toNumber', 'group1','group2','group3','group4','callduration','callStatus','callRecordingLink','branchId','created_at','updated_at'
    ];
}
