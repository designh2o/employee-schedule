<?php

namespace Tests\Unit;

use App\Helpers\ScheduleDay;
use App\Helpers\TimeRange;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleDayTest extends TestCase
{
	/** @var ScheduleDay $day */
	protected $day;

	protected function setUp()
	{
		parent::setUp();
		$this->day = new ScheduleDay(new Carbon('2019-01-01'));
	}

	protected function tearDown()
	{
		$this->day = null;
		parent::tearDown();
	}

	/**
	 * Тестирование получения количества диапозонов времени
	 */
	public function testGetCountRanges(){
		$range = new TimeRange('2019-01-01 10:00', '2019-01-01 15:00');
		$this->day->addRange($range);

		$this->assertEquals(1, $this->day->getCountRanges());
	}

	/**
	 * Тестирование определения выходного дня
	 */
	public function testCheckHoliday(){
		$this->assertTrue($this->day->checkHoliday());
	}

	/**
	 * Тестирование получения дня
	 */
	public function testGetDay(){
		$this->assertIsObject($this->day->getDay());
	}

	/**
	 * Тестирование вычитания из разсписания диапозона
	 * @dataProvider differenceRangesProvider
	 * @param $start
	 * @param $end
	 * @param $expected
	 */
	public function testDifferenceRanges($start, $end, $expected){
		$range = new TimeRange('2019-01-01 10:00', '2019-01-01 15:00');
		$this->day->addRange($range);
		$this->day->differenceRanges(new Carbon($start), new Carbon($end));

		$this->assertEquals($expected, $this->day->getCountRanges());
	}

	public function differenceRangesProvider() {
		return [
			['2019-01-01 11:00', '2019-01-01 12:00', 2],	//вычитаемый диапозон полностью входит исходный
			['2019-01-01 11:00', '2019-01-01 16:00', 1],	//вычитаемый диапозон частично входит исходный
			['2019-01-01 09:00', '2019-01-01 12:00', 1],	//вычитаемый диапозон частично входит исходный
			['2019-01-01 09:00', '2019-01-01 16:00', 0],	//вычитаемый диапозон полностью покрывает исходный
		];
	}

	/**
	 * Тестирование инвертирования временных промежутков
	 * @dataProvider inverseRangesProvider
	 * @param $start
	 * @param $end
	 * @param $expected
	 */
	public function testInverseRange($start, $end, $expected){
		$range = new TimeRange($start, $end);
		$this->day->addRange($range);
		$this->day->inverseRange();

		$this->assertEquals($expected, $this->day->getCountRanges());
	}

	public function inverseRangesProvider() {
		return [
			['2019-01-01 00:00', '2019-01-01 12:00', 1],	//совпадает с началом дня
			['2019-01-01 11:00', '2019-01-01 16:00', 2],	//по середине дня
			['2019-01-01 09:00', '2019-01-01 23:59', 1],	//совпадает с концом дня
		];
	}

	/**
	 * Тестирование метода toArray
	 * @throws \Exception
	 */
	public function testToArray(){
		$this->assertArrayHasKey('day', $this->day->toArray());
	}

	/**
	 * Тестирование проверки вхождения дня в интервал дат
	 * @dataProvider isIncludedPeriodProvider
	 * @param $start
	 * @param $end
	 * @param $expected
	 * @throws \ReflectionException
	 */
	public function testIsIncludedPeriod($start, $end, $expected){
		$method = new \ReflectionMethod(
			ScheduleDay::class, 'isIncludedPeriod'
		);
		$method->setAccessible(true);

		$this->assertEquals($expected, $method->invoke($this->day, new Carbon($start), new Carbon($end)));
	}

	public function isIncludedPeriodProvider() {
		return [
			['2018-01-01', '2019-01-01', true],
			['2019-01-01', '2019-01-02', true],
			['2019-01-01', '2019-01-01', true],
			['2019-01-02', '2019-01-03', false],
			['2019-01-02', '2019-01-01', false],
		];
	}
}
