<?php

namespace Tests\Unit;

use App\Helpers\Schedule;
use App\Helpers\ScheduleDay;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleTest extends TestCase
{
	protected $user;


	protected function setUp()
	{
		parent::setUp();
		$this->user = factory(\App\User::class)->make();
	}

	protected function tearDown()
	{
		$this->user = null;
		parent::tearDown();
	}

	/**
	 * Тестирование конструктора
	 *
	 * @dataProvider constructorProvider
	 * @param $startDate
	 * @param $endDate
	 * @param $failed
	 * @throws \Exception
	 */
	public function testConstruct($startDate, $endDate, $failed)
	{
		if($failed){
			$this->expectException(\Exception::class);
		}
		$schedule = new Schedule($this->user, $startDate, $endDate);

		$this->assertIsObject($schedule);
	}

	public function constructorProvider() {
		return [
			["2019-01-01", "2019-01-10", false],	//нормальные данные
			["2019-05-01", "2019-01-10", true],		//стартовая дата больше конечной
			["2019-01-01", "20aa19-01-10", true],	//неверный формат даты
		];
	}

	/**
	 * Тестирование получения количества дней
	 * @throws \Exception
	 */
	public function testGetCountDay(){
		$schedule = new Schedule($this->user, "2019-01-01", "2019-01-10");
		$day = new ScheduleDay(new Carbon('2019-01-01'));
		$schedule->addDay($day);

		$this->assertEquals(1, $schedule->getCountDay());
	}

	/**
	 * Тестирование расчета рабочих дней
	 * @dataProvider calculateWorkDaysProvider
	 * @throws \Exception
	 */
	public function testCalculateWorkDays($startDate, $endDate, $countDay) {
		$schedule = new Schedule($this->user, $startDate, $endDate);
		$schedule->calculateWorkDays();

		$this->assertEquals($countDay,$schedule->getCountDay());
	}

	public function calculateWorkDaysProvider() {
		return [
			["2019-01-01", "2019-01-10", 2],
			["2019-01-20", "2019-01-25", 5],
		];
	}

	/**
	 * Тестировние расчета свободных дней
	 * @dataProvider calculateFreeDaysProvider
	 * @param $startDate
	 * @param $endDate
	 * @param $countDay
	 * @throws \Exception
	 */
	public function testCalculateFreeDays($startDate, $endDate, $countDay){
		$schedule = new Schedule($this->user, $startDate, $endDate);
		$schedule->calculateFreeDays();

		$this->assertEquals($countDay,$schedule->getCountDay());
	}

	public function calculateFreeDaysProvider() {
		return [
			["2019-01-01", "2019-01-10", 10],
			["2019-01-20", "2019-01-25", 6],
		];
	}

	/**
	 * Тестирование метода toArray
	 * @throws \Exception
	 */
	public function testToArray(){
		$schedule = new Schedule($this->user, "2019-01-01", "2019-01-10");
		$schedule->calculateWorkDays();

		$this->assertArrayHasKey('schedule', $schedule->toArray());
	}

	/**
	 * Тестирование заполнения дня рабочим графиком
	 * @throws \Exception
	 * @throws \ReflectionException
	 */
	public function testSetWorkScheduleDay(){
		$schedule = new Schedule($this->user, "2019-01-01", "2019-01-10");
		$day = new ScheduleDay(new Carbon('2019-01-01'));
		$method = new \ReflectionMethod(
			Schedule::class, 'setWorkScheduleDay'
		);
		$method->setAccessible(true);
		/** @var ScheduleDay $day */
		$day = $method->invoke($schedule, $day);

		$this->assertEquals(2, $day->getCountRanges());
	}

	/**
	 * Тестирование заполнения полного заполнения дня
	 * @throws \Exception
	 * @throws \ReflectionException
	 */
	public function testSetFullScheduleDay(){
		$schedule = new Schedule($this->user, "2019-01-01", "2019-01-10");
		$day = new ScheduleDay(new Carbon('2019-01-01'));
		$method = new \ReflectionMethod(
			Schedule::class, 'setFullScheduleDay'
		);
		$method->setAccessible(true);
		/** @var ScheduleDay $day */
		$day = $method->invoke($schedule, $day);

		$this->assertEquals(1, $day->getCountRanges());
	}




}
