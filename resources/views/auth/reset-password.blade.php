Reset Password

<form action="/auth/reset-password" method="post">
    @csrf
    <input type="email" name="email" placeholder="Email" required>
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="password" name="password">
    <input type="password" name="password_confirmation">

    <button type="submit">Resend Verification Email</button>
</form>
