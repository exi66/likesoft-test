<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\User; //подразумевается что эта модель существует и имеет поля
// username — имя
// email — емейл
// validts — unix ts до которого действует ежемесячная подписка
// confirmed — 0 или 1
// getEmail — метод отношений hasOne, который возвращает объект электронной почты App\Models\Email
use App\Models\Email; //подразумевается что эта модель существует и имеет поля
// email — емейл
// checked — 0 или 1 (был ли проверен)
// valid — 0 или 1
// getUser — метод отношений belongsTo, который возвращает объект пользователя App\Models\User
use Illuminate\Support\Facades\DB;
use App\Mail\UserEmail;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            $now = time();
            $expire = $now + 60*60*24*3;

            $search_data = DB::table('users')
                ->join('emails', 'users.email', '=', 'emails.email')
                ->Where('users.confirmed', '=', 1)
                ->Where('users.validts', '<', $expire)
                ->Where('emails.valid', '=', 1)
                ->Where('emails.checked', '=', 0)
                ->pluck('users.id')
                ->toArray();

            $data = User::whereIn('id', $search_data)->get();

            foreach ($data as $user) {
                Mail::to($user->email)->send(new UserEmail($user));
                $email = $user->email;
                $email->checked = 1;
                $user->email()->save($email);
            }

        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
