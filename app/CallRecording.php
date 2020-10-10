<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallRecording extends Model
{
  
    use SoftDeletes;
      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'call_logs';

    /** 
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'call_sid','user_id','agent_phone_number','agent_name','from_number', 'to_number', 'group1','group2','group3','group4','call_duration','call_status','call_recording_link','call_direction','created_at','updated_at'
    ];

    public function scopeGroup($query, $value)
    {
        return $query->where('group4', $value);
    }
}
