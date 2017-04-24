<?php

namespace App\Traits;

use App\Models\WrestlerStatus;

trait HasStatuses {

	public $status_id;

	abstract public function status();

	public function isActive() {
		return $this->status() == WrestlerStatus::ACTIVE;
	}

	public function isInactive() {
		return $this->status() == WrestlerStatus::INACTIVE;
	}

	public function isInjured() {
		return $this->status() == WrestlerStatus::INJURED;
	}

	public function isSuspended() {
		return $this->status() == WrestlerStatus::SUSPENDED;
	}

	public function isRetired() {
		return $this->status() == WrestlerStatus::RETIRED;
	}

	public function scopeActive($query)
	{
		return $query->where('status_id', WrestlerStatus::ACTIVE);
	}

	public function scopeInactive($query)
	{
		return $query->where('status_id', WrestlerStatus::INACTIVE);
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

	public function setStatusToActive() {
		$this->update(['status_id' => WrestlerStatus::ACTIVE]);
	}

	public function setStatusToInactive() {
		$this->update(['status_id' => WrestlerStatus::INACTIVE]);
	}

	public function setStatusToInjured() {
		$this->update(['status_id' => WrestlerStatus::INJURED]);
	}

	public function setStatusToSuspended() {
		$this->update(['status_id' => WrestlerStatus::SUSPENDED]);
	}

	public function setStatusToRetired() {
		$this->update(['status_id' => WrestlerStatus::RETIRED]);
	}
}