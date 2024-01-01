<?php

declare(strict_types=1);

namespace App\Models;

trait NeedsApproval
{
    public static function bootNeedsApproval(): void
    {
        static::addGlobalScope(new ApprovableScope());
    }

    public function initializeNeedsApproval(): void
    {
        if (!isset($this->casts[$this->getApprovedAtColumn()])) {
            $this->casts[$this->getApprovedAtColumn()] = 'datetime';
        }
        $this->fillable[] = $this->getApprovedAtColumn();
    }

    public function approve(): bool
    {
        $this->{$this->getApprovedAtColumn()} = now();
        $result                               = $this->save();

        return $result;
    }

    public function isApproved(): bool
    {
        return !is_null($this->{$this->getApprovedAtColumn()});
    }

    public function disapprove(): bool
    {
        $this->{$this->getApprovedAtColumn()} = null;
        $result                               = $this->save();

        return $result;
    }

    public function getApprovedAtColumn(): string
    {
        return defined(static::class.'::APPROVED_AT') ? static::APPROVED_AT : 'approved_at';
    }

    public function getQualifiedApprovedAtColumn(): string
    {
        return $this->qualifyColumn($this->getApprovedAtColumn());
    }
}
