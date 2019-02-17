<?php

namespace Tests\Unit;

use App\Helpers\CalendarHelper;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalendarHelperTest extends TestCase
{
	/**
	 * Тестирование получения из апи информации о дне
	 */
	public function testGetFromApi(){
		$day = new Carbon('2019-01-01');
		$calendar = CalendarHelper::getFromApi($day);

		$this->assertArrayHasKey('work', $calendar);
	}

	/**
	 * Тестирование определения выходного дня
	 * @dataProvider checkHolidayProvider
	 * @param $day
	 */
	public function testCheckHoliday($day, $expected){
		$day = new Carbon($day);

		$this->assertEquals($expected ,CalendarHelper::checkHoliday($day));
	}

	public function checkHolidayProvider(){
		return [
			['2019-01-01', true],
			['2019-02-11', false],
		];
	}
}
