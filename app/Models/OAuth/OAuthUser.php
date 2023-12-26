<?php

declare(strict_types=1);

namespace App\Models\OAuth;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OAuthUser extends Model
{
    protected $table = 'oauth_users';

    protected $fillable = [
        'type',
        'oauth_id',
        'user_id',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
