<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Корпоративы
 * Class Corporate
 * @package App
 */
class Corporate extends Model
{
	public $timestamps = false;

	protected $dates = [
		'start',
		'end',
	];

	public $fillable = [
		'start',
		'end',
		'company_id',
	];

	/**
	 * Компания
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function company(){
		return $this->belongsTo(Company::class);
	}
}
