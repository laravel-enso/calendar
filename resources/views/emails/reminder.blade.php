@component('mail::message')
@component('mail::title')
{{ __('Reminder') }}
@endcomponent

{{ __('Hi :appellative', ['appellative' => $appellative]) }},

@component('mail::schedule', [
    'day' => $starts_at->format('d'),
    'month' => $starts_at->format('M'),
    'time' => $starts_at->format('H:i'),
    'label' => $title.' · '.$ends_at->format('H:i'),
])
@endcomponent

{{ __('To view the calendar click the button below.') }}

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ __('View Calendar') }}
@endcomponent

@component('mail::signature')
@endcomponent
@endcomponent
