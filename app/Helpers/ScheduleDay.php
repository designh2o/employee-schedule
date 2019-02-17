<?php
/**
 * Created by PhpStorm.
 * User: Andrey Dubinin
 * Date: 16.02.19
 * Time: 16:42
 */

namespace App\Helpers;


use App\Calendar;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Класс для хранения одного дня расписания
 * Class ScheduleDay
 * @package App\Helpers
 */
class ScheduleDay
{
	/** @var Collection */
	protected $timeRanges;	//расписание дня, состоящее из временных промежутков (TimeRange)

	/** @var Carbon  */
	protected $day;	//собственно день

	/**
	 * ScheduleDay constructor.
	 * @param Carbon $day
	 */
	public function __construct(Carbon $day)
	{
		$this->day = $day;
		$this->timeRanges = new Collection();
	}

	/**
	 * Получить день
	 * @return Carbon
	 */
	public function getDay(){
		return $this->day->copy();
	}

	/**
	 * Получить количество временных диапозонов
	 * @return int
	 */
	public function getCountRanges(){
		return $this->timeRanges->count();
	}

	/**
	 * Является ли день выходным
	 * @return bool
	 */
	public function checkHoliday(){
		return CalendarHelper::checkHoliday($this->day);
	}

	/**
	 * Добавление временного промежутка в рассписание дня
	 * @param TimeRange $range
	 */
	public function addRange(TimeRange $range)
	{
		$this->timeRanges->push($range);
	}

	/**
	 * Вычесть из расписания промежуток времени
	 * возвращает false, если в расписании дня нет диапозонов
	 * @param Carbon $start
	 * @param Carbon $end
	 * @return bool
	 */
	public function differenceRanges(Carbon $start, Carbon $end){
		if($this->isIncludedPeriod($start, $end)){	//день входит в промежуток
			/** @var TimeRange $range */
			foreach($this->timeRanges as $key => $range){
				$rangeStart = $range->getStartDate();
				$rangeEnd = $range->getEndDate();

				if($rangeStart <= $end && $rangeEnd >= $start){	//интервалы времени пересекаются
					if($rangeStart >= $start && $rangeEnd <= $end){	//рабочее время полностью входит в свободное
						$this->timeRanges->offsetUnset($key);	//удаляем временной промежуток
						continue;
					}elseif($start >= $rangeStart && $end <= $rangeEnd){	//свободное время полностью входит в рабочее
						//"Разделяем" промежуток времени на две части
						$newRange = new TimeRange($end, $rangeEnd);
						$this->addRange($newRange);
						$range->setEndDate($start);
					}elseif($start > $rangeStart){	//свободное время больше рабочего
						$range->setEndDate($start);	//сдвигаем конец промежутка
					}elseif($end < $rangeEnd){	//свободное время меньше рабочего
						$range->setStartDate($end);	//сдвигаем начало промежутка
					}
				}
			}
			if(empty($this->timeRanges)){	//в расписании дня не оказалось диапозонов, возвращаем false
				return false;
			}
		}
		return true;
	}

	/**
	 * Инвертирование диапозонов времени
	 */
	public function inverseRange()
	{
		$this->sortRanges();
		$newRanges = new Collection();	//новое расписание дня
		$totalCount = count($this->timeRanges);	//общее кол-во диапозонов
		$counter = 0;
		$dayStart = $this->day->copy()->setTime(0,0);	//начало дня
		$dayEnd = $this->day->copy()->setTime(23,59);	//конец дня
		foreach($this->timeRanges as $key => $range){
			$counter++;
			$rangeStart = $range->getStartDate();
			$rangeEnd = $range->getEndDate();
			if($counter == 1){	//первый интервал
				$prevRangeEnd = $dayStart;	//начинаем с начала дня 00:00
			}else{
				$prevRangeEnd = $this->timeRanges[$key - 1]->getEndDate();	//конец предыдущего диапозона
			}
			if($counter == $totalCount) {	//последний интервал
				$nextRangeStart = $dayEnd;	//заканчиваем концом дня 23:59
			}else{
				$nextRangeStart = $this->timeRanges[$key + 1]->getStartDate();	//начало следуещего диапозона
			}
			if($dayStart != $rangeStart){	//если промежуток не начинается с днем
				$newRanges->push(new TimeRange($prevRangeEnd, $rangeStart));	//добавляем предыдущий промежуток
			}
			if($counter == $totalCount && $dayEnd != $rangeEnd){	//последний интервал и конец не совпадает с концом дня
				$newRanges->push(new TimeRange($rangeEnd, $nextRangeStart));	//добавляем оставшийся в конце промежуток
			}
		}
		unset($this->timeRanges);
		$this->timeRanges = $newRanges;
	}

	/**
	 * Сортировать диапозоны
	 */
	public function sortRanges(){
		$this->timeRanges->sort();
	}

	public function toArray(){
		$this->sortRanges();
		$result = [
			'day' => $this->day->format('Y-m-d'),
			'timeRanges' => []
		];
		/** @var TimeRange $range */
		foreach($this->timeRanges as $range){
			$result['timeRanges'][] = $range->toArray();
		}
		return $result;
	}

	/**
	 * День входит в период дат без учета времени
	 * @param Carbon $start
	 * @param Carbon $end
	 * @return bool
	 */
	protected function isIncludedPeriod(Carbon $start, Carbon $end){
		return $start->diffInDays($this->day, false) >= 0 &&
			$end->diffInDays($this->day, false) <= 0;
	}
}