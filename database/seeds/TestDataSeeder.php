<?php
/**
 * Created by PhpStorm.
 * User: Andrey Dubinin
 * Date: 16.02.19
 * Time: 18:50
 */

use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
	public function run(){
		//создаем компанию
		$company = \App\Company::create([
			'name' => 'test_company'
		]);

		//создаем пользователя
		$user = App\User::create([
			'name' => 'test_user',
			'email' => 'test@test.test',
			'password' => bcrypt('123456'),
			'company_id' => $company->id,
			'work_start' => '09:00',
			'work_end' => '19:00',
			'lunch_start' => '13:00',
			'lunch_end' => '14:00',
		]);

		//создаем корпоратив
		\App\Corporate::create([
			'start' => "2019-01-10 15:00:00",
			'end' => "2019-01-11 00:00:00",
			'company_id' => $company->id
		]);

		//создаем отпуск
		\App\Vacation::create([
			'start' => "2019-01-11 00:00:00",
			'end' => "2019-01-15 00:00:00",
			'user_id' => $user->id
		]);
	}
}