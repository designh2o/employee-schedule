<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 16.02.19
 * Time: 17:52
 */

namespace App\Console\Commands;

use App\Calendar;
use App\Helpers\CalendarHelper;
use Illuminate\Console\Command;

class GetCalendar extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'employee:get-calendar';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Получение производственного календаря';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->info('start');
		$calendars = CalendarHelper::getFromApi();
		if(is_array($calendars) && !empty($calendars)) {
			foreach ($calendars as $calendar) {
				Calendar::create($calendar);
			}
		}else{
			$this->error('Не верный ответ апи!');
		}
		$this->info('finish');
	}
}