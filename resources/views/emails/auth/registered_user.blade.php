<x-mail::message>

Welcome to Idea Hub, {{ $user->username }}!

<x-mail::button :url="$dashboard_url">
Go To Your Dashboard
</x-mail::button>

</x-mail::message>
