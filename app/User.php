<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'company_id', 'work_start', 'work_end', 'lunch_start', 'lunch_end'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * Расписание дня поумолчанию
	 * @var array
	 */
	protected $defaultValue = [
		'work_start' => '09:00',
		'work_end' => '18:00',
		'lunch_start' => '13:00',
		'lunch_end' => '14:00',
	];

	/**
	 * Отпуски пользователя
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function vacations(){
		return $this->hasMany(Vacation::class);
	}

	/**
	 * Компания пользователя
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function company(){
		return $this->belongsTo(Company::class);
	}

	/**
	 * Получить рабочее расписание дня пользователя
	 * @return array
	 */
	public function getScheduleDayAttribute(){
		$workStart = explode(":", $this->work_start ?? $this->defaultValue['work_start']);
		$workEnd = explode(":", $this->work_end ?? $this->defaultValue['work_end']);
		$lunchStart = explode(":", $this->lunch_start ?? $this->defaultValue['lunch_start']);
		$lunchEnd = explode(":", $this->lunch_end ?? $this->defaultValue['lunch_end']);
		$result = [];
		if(!empty($workStart) && !empty($workEnd)){
			$result['work'] = [
				'start' => [
					'hour' => $workStart[0],
					'minute' => $workStart[1],
				],
				'end' => [
					'hour' => $workEnd[0],
					'minute' => $workEnd[1],
				],
			];
		}
		if(!empty($lunchStart) && !empty($lunchEnd)){
			$result['lunch'] = [
				'start' => [
					'hour' => $lunchStart[0],
					'minute' => $lunchStart[1],
				],
				'end' => [
					'hour' => $lunchEnd[0],
					'minute' => $lunchEnd[1],
				],
			];
		}
		return $result;
	}
}
