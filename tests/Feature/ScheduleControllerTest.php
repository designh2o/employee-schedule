<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleControllerTest extends TestCase
{
	/**
	 * Тестирование структуры ответа получения рабочего расписания
	 *
	 * @return void
	 */
	public function testGetWorkSchedule()
	{
		$this->json('GET', '/schedule', ['userId' => 1, 'startDate' => '2019-01-01', 'endDate' => '2019-01-10'])
			->assertJsonStructure([
				'schedule' => [
					[
						'day',
						'timeRanges' => [
							[
								'start',
								'end'
							]
						]
					]
				]
			]);
	}

	/**
	 * Тестирование структуры ответа получения рабочего расписания
	 *
	 * @return void
	 */
	public function testGetFreeSchedule()
	{
		$this->json('GET', '/schedule-free', ['userId' => 1, 'startDate' => '2019-01-01', 'endDate' => '2019-01-10'])
			->assertJsonStructure([
				'schedule' => [
					[
						'day',
						'timeRanges' => [
							[
								'start',
								'end'
							]
						]
					]
				]
			]);
	}
}
