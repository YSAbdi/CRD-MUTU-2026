<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('model:prune')->dailyAt('02:00');
Schedule::call(function () { logger()->info('CRM daily operational report scheduled.'); })->dailyAt('18:00');
