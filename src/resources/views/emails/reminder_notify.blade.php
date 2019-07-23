@component('mail::message')
{{ __('Hi :appellative', ['appellative' => $appellative]) }},

@component('mail::panel')
    {{ $title }} @ {{$starts_at}} to {{$ends_at}}
@endcomponent

{{ __('To view the calendar click the button below.') }}

@component('mail::button', ['url' => $url, 'color' => 'green'])
{{ __('View Calendar') }}
@endcomponent

{{ __('Thank you') }},<br>
{{ __(config('app.name')) }}
@endcomponent
