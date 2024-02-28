<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;

class SentEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sent-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to remind attendees.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $events = Event::with('attendees.user')->whereBetween('start_date', [now()->addDays(3), now()->addDays(4)])->get();
        $eventCount = count($events);
        $this->info("There are " . $eventCount . " Events");
        $events->each(fn ($event) => $event->attendees()->each(fn ($attendee) => $attendee->user->notify(new EventReminderNotification($event))));
        $this->info('Reminder succeed');
    }
}
