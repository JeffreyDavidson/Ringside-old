<?php

namespace App\Traits;

use App\Models\WrestlerStatus;

trait HasStatuses
{
    abstract public function status();

    public function isActive()
    {
        return $this->status_id == WrestlerStatus::where('name', 'Active')->get()->id;
    }

    public function isInactive()
    {
        return $this->status_id == WrestlerStatus::where('name', 'Inactive')->get()->id;
    }

    public function scopeHasStatus($query, $status)
    {
        return $query->where('status_id', WrestlerStatus::where('name', $status));
    }

    public function setStatusToActive()
    {
        $this->update(['status_id' => WrestlerStatus::where('name', 'Active')->get()->id]);

        return $this;
    }

    public function setStatusToInactive()
    {
        $this->update(['status_id' => WrestlerStatus::where('name', 'Inactive')->get()->id]);

        return $this;
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
