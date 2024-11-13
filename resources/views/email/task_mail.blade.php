

@component('mail::message')
# Hello

{{ $content }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent