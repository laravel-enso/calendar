<?php

namespace LaravelEnso\Calendar;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use LaravelEnso\Mails\Preview\PreviewDefinition;
use LaravelEnso\Mails\Preview\PreviewRegistry;

class MailServiceProvider extends ServiceProvider
{
    public function boot(PreviewRegistry $registry): void
    {
        $registry->register(new PreviewDefinition(
            key: 'calendar-reminder',
            name: 'Calendar Reminder',
            view: 'laravel-enso/calendar::emails.reminder',
            data: [
                'appellative' => 'Jane',
                'url' => 'https://example.com/calendar',
                'title' => 'Operations review',
                'starts_at' => Carbon::parse('2026-05-27 09:30'),
                'ends_at' => Carbon::parse('2026-05-27 10:30'),
            ],
            section: PreviewDefinition::Core,
        ));
    }
}
