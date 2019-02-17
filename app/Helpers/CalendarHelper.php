<?php
/**
 * Created by PhpStorm.
 * User: Andrey Dubinin
 * Date: 17.02.19
 * Time: 19:48
 */

namespace App\Helpers;


use App\Calendar;
use Carbon\Carbon;

/**
 * Класс для работы с производственным календарем
 * Class CalendarHelper
 * @package App\Helpers
 */
class CalendarHelper
{
	/**
	 * Получаем календарь из апи
	 * @param Carbon|null $day
	 * @return mixed
	 */
	public static function getFromApi(Carbon $day = null){
		$url = \Config::get('app.api_calendar');
		if($day){
			$url .= '?day='.$day->format('Y-m-d');
		}
		$calendars = json_decode(file_get_contents($url),true);
		return $calendars;
	}

	/**
	 * Возвращает true, если день выходной
	 * @param Carbon $day
	 * @return bool
	 */
	public static function checkHoliday(Carbon $day){
		$calendar = Calendar::where('day', $day)->first();
		if($calendar){
			return !$calendar->work;
		}
		//день не найден, пробуем получить из апи
		$calendar = self::getFromApi($day);
		if(is_array($calendar) && !empty($calendar)){
			$calendar = Calendar::create($calendar);
			return !$calendar->work;
		}
		return false;
	}
}