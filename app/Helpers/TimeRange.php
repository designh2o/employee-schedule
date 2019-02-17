<?php
/**
 * Created by PhpStorm.
 * User: Andrey Dubinin
 * Date: 16.02.19
 * Time: 16:40
 */

namespace App\Helpers;


use Carbon\CarbonPeriod;
use ReflectionClass;

/**
 * Класс для хранения одного диапозона времени
 * Class TimeRange
 * @package App\Helpers
 */
class TimeRange extends CarbonPeriod
{
	public function toArray()
	{
		$result = [
			'start' => $this->startDate->format('Hi'),
			'end' => $this->endDate->format('Hi'),
		];

		return $result;
	}

	/**
	 * Create a new instance from an array of parameters.
	 *
	 * @param array $params
	 *
	 * @return static
	 */
	public static function createFromArray(array $params)
	{
		// PHP 5.3 equivalent of new static(...$params).
		$reflection = new ReflectionClass(get_class());
		/** @var static $instance */
		$instance = $reflection->newInstanceArgs($params);

		return $instance;
	}
}