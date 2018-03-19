<?php

namespace App\Traits;

use App\Models\WrestlerStatus;

trait HasStatuses
{
    abstract public function status();

    public function isActive()
    {
        return $this->status_id == WrestlerStatus::where('name', 'Active')->first()->id;
    }

    public function isInactive()
    {
        return $this->status_id == WrestlerStatus::where('name', 'Inactive')->first()->id;
    }

    public function scopeHasStatus($query, $status)
    {
        $status = WrestlerStatus::where('name', $status)->first();

        return $query->where('status_id', $status->id);
    }

    public function setStatusToActive()
    {
        $status = WrestlerStatus::where('name', 'Active')->first();

        $this->update(['status_id' => $status->id]);

        return $this;
    }

    public function setStatusToInactive()
    {
        $status = WrestlerStatus::where('name', 'Inactive')->first();

        $this->update(['status_id' => $status->id]);

        return $this;
    }

    /**
     * Return the available statuses for this entity.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function availableStatuses()
    {
        $status = $this->status_id ? $this->status->name : null;

        return WrestlerStatus::available($status)->get();
    }

    // TODO: Adjust job of changing the status of the wrestler.
    /*
     * Checks to see if wrestler is no longer retired, injured or suspended.
     *
     * @return void
     */
    //public function statusChanged()
    //{
    //    if ($this->status() == WrestlerStatus::RETIRED) {
    //        $this->unretire();
    //    } else if ($this->status() == WrestlerStatus::INJURED) {
    //        $this->heal();
    //    } else if ($this->status() == WrestlerStatus::SUSPENDED) {
    //        $this->rejoin();
    //    }
    //}
}
