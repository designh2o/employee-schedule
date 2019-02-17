<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Calendar
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $day
 * @property bool $work
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Calendar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Calendar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Calendar query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Calendar whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Calendar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Calendar whereWork($value)
 */
	class Calendar extends \Eloquent {}
}

namespace App{
/**
 * App\Company
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Corporate[] $corporates
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereUpdatedAt($value)
 */
	class Company extends \Eloquent {}
}

namespace App{
/**
 * App\Corporate
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $start
 * @property \Illuminate\Support\Carbon $end
 * @property int $company_id
 * @property-read \App\Company $company
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Corporate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Corporate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Corporate query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Corporate whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Corporate whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Corporate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Corporate whereStart($value)
 */
	class Corporate extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $work_start
 * @property string $work_end
 * @property string $lunch_start
 * @property string $lunch_end
 * @property int|null $company_id
 * @property-read \App\Company|null $company
 * @property-read mixed $schedule_day
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Vacation[] $vacations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLunchEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLunchStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereWorkEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereWorkStart($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Vacation
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $start
 * @property \Illuminate\Support\Carbon $end
 * @property int $user_id
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacation whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacation whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vacation whereUserId($value)
 */
	class Vacation extends \Eloquent {}
}

