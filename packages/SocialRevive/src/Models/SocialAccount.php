<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
