<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','group1','group2','group3','group4','level','is_admin','is_callable', 'designation', 'phone_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

      /**
     * Get the post that owns the comment.
     */
    public function region()
    {
        return $this->belongsTo(RegionMaster::class,'group1','region_id');
    }

        /**
     * Get the post that owns the comment.
     */
    public function branch()
    {
        return $this->belongsTo(BranchMaster::class,'group2','branch_id');
    }

        /**
     * Get the post that owns the comment.
     */
    public function zone()
    {
        return $this->belongsTo(ZoneMaster::class,'group3','zone_id');
    }

        /**
     * Get the post that owns the comment.
     */
    public function megaZone()
    {
        return $this->belongsTo(MegaZoneMaster::class,'group4','mega_zone_id');
    }
    
}
