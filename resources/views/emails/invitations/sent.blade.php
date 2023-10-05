<x-mail::message>

The {{ $team->name}} team would like you to join them!
<br>
By accepting the invitation below, you will be able to collaborate and chat with other members in the team!
<br>
Bur hurry! This invitation will expire in 7 days!
<br>

<x-mail::button :url="$url">
Accept Invitation
</x-mail::button>

</x-mail::message>
