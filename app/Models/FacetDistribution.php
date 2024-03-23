<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FacetDistribution extends Model
{
    // trick for gql to tell models apart
    protected $primaryKey = 'collection_id';
    protected $fillable   = [
        'count',
        'collection_id',
    ];

    public function collection(): HasOne
    {
        return $this->hasOne(Collection::class, 'id', 'collection_id');
    }
}
