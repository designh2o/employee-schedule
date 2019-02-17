<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Компании
 * Class Company
 * @package App
 */
class Company extends Model
{

	public $fillable = [
		'name',
	];

	/**
	 * Корпоративы
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function corporates(){
		return $this->hasMany(Corporate::class);
	}

	/**
	 * Пользователи
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function users(){
		return $this->hasMany(User::class);
	}
}
