<?php
/**
 * Created by PhpStorm.
 * User: Andrey Dubinin
 * Date: 16.02.19
 * Time: 20:59
 */

namespace App\Helpers;


use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

/**
 * Основной класс расписания
 * Class Schedule
 * @package App\Helpers
 */
class Schedule
{
	protected $days;
	protected $user;
	protected $period;

	/**
	 * Schedule constructor.
	 * @param User $user
	 * @param $start
	 * @param $end
	 * @throws \Exception
	 */
	public function __construct(User $user, $start, $end)
	{
		$this->user = $user;
		if($start > $end){
			throw new \Exception('start date greater end date');
		}
		$this->period = CarbonPeriod::create($start, $end);
		$this->days = new Collection();
	}

	/**
	 * Добавление дня
	 * @param ScheduleDay $day
	 */
	public function addDay(ScheduleDay $day){
		$this->days->push($day);
	}

	/**
	 * Получить количество дней
	 * @return int
	 */
	public function getCountDay(){
		return $this->days->count();
	}

	/**
	 * Рассчет рабочих дней
	 * Выходные дни пропускаем, заполняем рабочие дни рабочим расписанием, потом отнимаем корпоративы и отпуски
	 */
	public function calculateWorkDays(){
		/**
		 * @var  $key
		 * @var Carbon $date
		 */
		foreach ($this->period as $key => $date) {
			$day = new ScheduleDay($date);
			if($day->checkHoliday()){	//проверяем, не выходной ли день
				continue;
			}
			$this->setWorkScheduleDay($day);	//устанавливаем рабочее расписания для дня
			//вычитаем корпоративы
			if($this->user->company) {	//привязана ли компания к пользователю
				$corporates = $this->user->company->corporates;
				if ($corporates) {	//есть ли корпоративы
					foreach ($corporates as $corporate) {
						//вычитаем корпоратив из расписания дня
						if (!$day->differenceRanges($corporate->start, $corporate->end)) {
							//день получился пустой, не добавляем
							unset($day);
							continue 2;	//переходим к следующему дню
						}
					}
				}
			}

			if($this->user->vacations) {
				//вычитаем отпуски
				$vacations = $this->user->vacations;
				if ($vacations) {
					foreach ($vacations as $vacation) {
						//вычитаем отпуск из расписания дня
						if (!$day->differenceRanges($vacation->start, $vacation->end)) {
							//день пустой, не добавляем
							unset($day);
							continue 2;	//переходим к следующему дню
						}
					}
				}
			}
			$this->addDay($day);	//добавляем день в расписание
		}
	}

	/**
	 * Рассчет выходных дней
	 * Добавляем выходные дни, рабочие дни заполняем обычным графиком, потом инвертируем диапозоны времени
	 */
	public function calculateFreeDays(){
		/**
		 * @var  $key
		 * @var Carbon $date
		 */
		foreach ($this->period as $key => $date) {
			$needInverse = false;	//флаг, нужно ли инверитровать диапозоны
			$day = new ScheduleDay($date);
			if($day->checkHoliday()) {	//выходной - добавляем полностью
				$this->setFullScheduleDay($day);
			}else{	//не выходной - считаем рабочее время и инвертируем диапозоны
				$this->setWorkScheduleDay($day);
				//вычитаем корпоративы
				if($this->user->company) {
					$corporates = $this->user->company->corporates;
					if ($corporates) {
						foreach ($corporates as $corporate) {
							//вычитаем корпоратив из расписания дня
							if ($day->differenceRanges($corporate->start, $corporate->end)) {
								//если день получился не пустой, значит потребуется инвертировать его
								$needInverse = true;
							}
						}
					}
				}

				if($this->user->vacations) {
					//вычитаем отпуски
					$vacations = $this->user->vacations;
					if ($vacations) {
						foreach ($vacations as $vacation) {
							if ($day->differenceRanges($vacation->start, $vacation->end)) {
								//инвертируем
								$needInverse = true;
							}
						}
					}
				}
				if($needInverse){	//день оказался непустой, инвертируем временные диапозоны
					$day->inverseRange();
				}else{	//день оказался пустой, добавляем весь день
					$this->setFullScheduleDay($day);
				}
			}
			$this->addDay($day);
		}
	}

	public function toArray(){
		$result = [
			'schedule' => [],
		];
		/** @var ScheduleDay $day */
		foreach($this->days as $day){
			$result['schedule'][] = $day->toArray();
		}
		return $result;
	}

	/**
	 * Установка рабочего расписания дня
	 * @param ScheduleDay $day
	 * @return ScheduleDay
	 */
	protected function setWorkScheduleDay(ScheduleDay $day){
		$scheduleDay = $this->user->schedule_day;
		if(isset($scheduleDay['work'])) {
			if(!isset($scheduleDay['lunch'])){	//без обеда:(
				$day->addRange(TimeRange::create(
					$day->getDay()->setTime(
						$this->user->schedule_day['work']['start']['hour'],
						$this->user->schedule_day['work']['start']['minute']),
					$day->getDay()->setTime(
						$this->user->schedule_day['work']['end']['hour'],
						$this->user->schedule_day['work']['end']['minute'])
				));
			}else {
				$day->addRange(TimeRange::create(
					$day->getDay()->setTime(
						$this->user->schedule_day['work']['start']['hour'],
						$this->user->schedule_day['work']['start']['minute']),
					$day->getDay()->setTime(
						$this->user->schedule_day['lunch']['start']['hour'],
						$this->user->schedule_day['lunch']['start']['minute'])
				));
				$day->addRange(TimeRange::create(
					$day->getDay()->setTime(
						$this->user->schedule_day['lunch']['end']['hour'],
						$this->user->schedule_day['lunch']['end']['minute']),
					$day->getDay()->setTime(
						$this->user->schedule_day['work']['end']['hour'],
						$this->user->schedule_day['work']['end']['minute'])
				));
			}
		}
		return $day;
	}

	/**
	 * Полное заполнение дня от 00:00 до 23:59
	 * @param ScheduleDay $day
	 * @return ScheduleDay
	 */
	protected function setFullScheduleDay(ScheduleDay $day){
		$day->addRange(TimeRange::create(
			$day->getDay()->setTime(0, 0),
			$day->getDay()->setTime(23,59)));
		return $day;
	}
}