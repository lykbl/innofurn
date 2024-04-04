<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailChangeHistory extends Model
{
    use SoftDeletes;

    protected $table    = 'email_change_history';
    protected $fillable = ['email', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
