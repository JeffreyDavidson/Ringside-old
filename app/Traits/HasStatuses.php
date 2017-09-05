<?php

namespace App\Traits;

use App\Models\WrestlerStatus;

trait HasStatuses
{
    public $status_id;

    abstract public function status();

    public function isActive()
    {
        return $this->status() == WrestlerStatus::ACTIVE;
    }

    public function isInactive()
    {
        return $this->status() == WrestlerStatus::INACTIVE;
    }

    public function scopeActive($query)
    {
        return $query->where('status_id', WrestlerStatus::ACTIVE);
    }

    public function scopeInjured($query)
    {
        return $query->where('status_id', WrestlerStatus::INJURED);
    }

    public function scopeSuspended($query)
    {
        return $query->where('status_id', WrestlerStatus::SUSPENDED);
    }

    public function scopeRetired($query)
    {
        return $query->where('status_id', WrestlerStatus::RETIRED);
    }

    public function scopeInactive($query)
    {
        return $query->where('status_id', WrestlerStatus::INACTIVE);
    }

    public function setStatusToActive()
    {
        $this->update(['status_id' => WrestlerStatus::ACTIVE]);
    }

    public function setStatusToInactive()
    {
        $this->update(['status_id' => WrestlerStatus::INACTIVE]);
    }
}