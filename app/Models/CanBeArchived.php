<?php

declare(strict_types=1);

namespace App\Models;

trait CanBeArchived
{
    public function initializeCanBeArchived(): void
    {
        if (!isset($this->casts[$this->getArchivedAtColumn()])) {
            $this->casts[$this->getArchivedAtColumn()] = 'datetime';
        }
        $this->fillable[] = $this->getArchivedAtColumn();
    }

    public function archive(): bool
    {
        $this->{$this->getArchivedAtColumn()} = now();
        $result                               = $this->save();

        return $result;
    }

    public function unarchive(): bool
    {
        $this->{$this->getArchivedAtColumn()} = null;
        $result                               = $this->save();

        return $result;
    }

    public function isArchived(): bool
    {
        return !is_null($this->{$this->getArchivedAtColumn()});
    }

    public function getArchivedAtColumn(): string
    {
        return defined(static::class.'::ARCHIVED_AT') ? static::ARCHIVED_AT : 'archived_at';
    }

    public function getQualifiedArchivedAtColumn(): string
    {
        return $this->qualifyColumn($this->getArchivedAtColumn());
    }
}
