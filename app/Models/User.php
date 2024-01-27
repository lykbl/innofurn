<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\CustomerGroups\CustomerGroupTypes;
use App\Models\OAuth\OAuthUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Lunar\Base\Traits\LunarUser;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use LunarUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_override',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function oauthUser(): HasOne
    {
        return $this->hasOne(OAuthUser::class, 'user_id', 'id');
    }

    public function retailCustomers(): BelongsToMany
    {
        return $this
            ->customers()
            ->whereHas('customerGroups', function ($query): void {
                $query->where('handle', CustomerGroupTypes::Retail->value);
            });
    }

    public function retailCustomer(): HasOneThrough
    {
        return $this
            ->hasOneThrough(Customer::class, CustomerUserPivot::class, 'user_id', 'id', 'id', 'customer_id')
            ->whereHas('customerGroups', function ($query): void {
                $query->where('handle', CustomerGroupTypes::Retail->value);
            });
    }
}
