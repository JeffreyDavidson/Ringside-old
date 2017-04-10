<?php

namespace App\Traits;

use App\Status;

trait HasStatuses {

	public $status_id;

	abstract public function status();

	public function isActive() {
		return $this->status_id == Status::ACTIVE;
	}

	public function isInactive() {
		return $this->status_id == Status::INACTIVE;
	}

	public function isInjured() {
		return $this->status_id == Status::INJURED;
	}

	public function isSuspended() {
		return $this->status_id == Status::SUSPENDED;
	}

	public function isRetired() {
		return $this->status_id == Status::RETIRED;
	}

	public function scopeActive($query)
	{
		return $query->where('status_id', Status::ACTIVE);
	}

	public function scopeInactive($query)
	{
		return $query->where('status_id', Status::INACTIVE);
	}

	public function scopeInjured($query)
	{
		return $query->where('status_id', Status::INJURED);
	}

	public function scopeSuspended($query)
	{
		return $query->where('status_id', Status::SUSPENDED);
	}

	public function scopeRetired($query)
	{
		return $query->where('status_id', Status::RETIRED);
	}

	public function setStatusToActive() {
		$this->update(['status_id' => Status::ACTIVE]);
	}

	public function setStatusToInactive() {
		$this->update(['status_id' => Status::INACTIVE]);
	}

	public function setStatusToInjured() {
		$this->update(['status_id' => Status::INJURED]);
	}

	public function setStatusToSuspended() {
		$this->update(['status_id' => Status::SUSPENDED]);
	}

	public function setStatusToRetired() {
		$this->update(['status_id' => Status::RETIRED]);
	}
}