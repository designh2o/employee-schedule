<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Отпуски
 * Class Vacation
 * @package App
 */
class Vacation extends Model
{
	public $timestamps = false;

	protected $dates = [
		'start',
		'end'
	];

	public $fillable = [
		'start',
		'end'
	];

	/**
	 * Пользователь
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user(){
		return $this->belongsTo(User::class);
	}
}
