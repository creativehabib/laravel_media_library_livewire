<?php

use Illuminate\Database\Eloquent\Model;

class SocialPostQueue extends Model
{
    protected $guarded = [];

    protected $casts = [
        'media' => 'array',
        'utm_data' => 'array',
        'scheduled_at' => 'datetime',
        'posted_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(SocialAccount::class, 'social_account_id');
    }
}
