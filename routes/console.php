<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('crm:booking-reminders')->hourly()->withoutOverlapping();
Schedule::command('crm:backup --disk=backups')->dailyAt('01:30')->withoutOverlapping();
Schedule::command('crm:prune-backups --disk=backups')->dailyAt('02:00')->withoutOverlapping();
Schedule::command('model:prune')->dailyAt('02:30');
