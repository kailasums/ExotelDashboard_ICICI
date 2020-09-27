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
        'from_number', 'to_number', 'group1','group2','group3','group4','call_duration','call_status','call_recording_link','call_direction','created_at','updated_at'
    ];

    public function scopeGroup($query, $value)
    {
        return $query->where('group4', $value);
    }
}
