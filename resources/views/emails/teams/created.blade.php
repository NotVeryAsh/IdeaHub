<x-mail::message>

Hey {{ $user->username }}!,
<br>
Your new team, {{ $team->name }}, has been created!,
<br>

<x-mail::button :url="$membersPageURL">
Invite Members
</x-mail::button>

</x-mail::message>
