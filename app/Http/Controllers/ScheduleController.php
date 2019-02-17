<?php

namespace App\Http\Controllers;

use App\Helpers\Schedule;
use App\Http\Requests\ScheduleRequest;
use App\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
	/**
	 * Получить рабочее расписание пользователя
	 * @param ScheduleRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getWorkSchedule(ScheduleRequest $request){
		try {
			/** @var User $user */
			$user = User::findOrFail($request->userId);
			$schedule = new Schedule($user, $request->startDate, $request->endDate);
			$schedule->calculateWorkDays();
			return response()->json($schedule->toArray(), 200);
		}catch (\Exception $e){
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}

	/**
	 * Получить нерабочее расписание пользователя
	 * @param ScheduleRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getFreeSchedule(ScheduleRequest $request){
		try {
			/** @var User $user */
			$user = User::findOrFail($request->userId);
			$schedule = new Schedule($user, $request->startDate, $request->endDate);
			$schedule->calculateFreeDays();
			return response()->json($schedule->toArray(), 200);
		}catch (\Exception $e){
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}
}
