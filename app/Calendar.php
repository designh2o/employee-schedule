<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Производственный календарь
 * Class Calendar
 * @package App
 */
class Calendar extends Model
{
	public $timestamps = false;

	protected $dates = [
		'day'
	];

	public $fillable = [
		'day',
		'work'
	];

	protected $casts = [
		'work' => 'boolean',	//рабочий ли день
	];
}
