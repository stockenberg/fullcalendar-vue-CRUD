<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

	public function getStartAttribute($value)
	{
		if ($this->allDay) {
			return date("Y-m-d", strtotime($value));
		}

		return $value;


	}

	public function getEndAttribute($value)
	{
		if ($this->allDay) {
			return date("Y-m-d", strtotime($value));
		}

		return $value;

	}

}
