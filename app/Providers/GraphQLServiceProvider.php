<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;

final class GraphQLServiceProvider extends ServiceProvider
{
    public function boot(TypeRegistry $typeRegistry): void
    {
    }
}
