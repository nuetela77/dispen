<?php
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\UpdateStatusIzin;

Schedule::command(UpdateStatusIzin::class)
    ->everyMinute()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/scheduler.log'));