<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ApprovableScope implements Scope
{
    private $extensions = ['Approve', 'Disapprove', 'WithUnapproved', 'WithoutApproved', 'OnlyApproved'];

    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereNotNull('approved_at');
    }

    public function extend(Builder $builder): void
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    protected function addApprove(Builder $builder): void
    {
        $builder->macro('approve', function (Builder $builder): int {
            return $builder->update([
                $builder->getModel()->getQualifiedApprovedAtColumn() => now(),
            ]);
        });
    }

    protected function addDisapprove(Builder $builder): void
    {
        $builder->macro('disapprove', function (Builder $builder): int {
            return $builder->update([
                $builder->getModel()->getQualifiedApprovedAtColumn() => null,
            ]);
        });
    }

    protected function addWithUnapproved(Builder $builder): void
    {
        $builder->macro('withUnapproved', function (Builder $builder): Builder {
            return $builder->withoutGlobalScope($this);
        });
    }

    protected function addWithoutApproved(Builder $builder): void
    {
        $builder->macro('withoutApproved', function (Builder $builder): Builder {
            return $builder->withoutGlobalScope($this)->whereNull('approved_at');
        });
    }

    protected function addOnlyApproved(Builder $builder): void
    {
        $builder->macro('onlyApproved', function (Builder $builder): Builder {
            return $builder->withoutGlobalScope($this)->whereNotNull('approved_at');
        });
    }
}
