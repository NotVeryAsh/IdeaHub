Forgot Password

<form action="/auth/forgot-password" method="post">
    @csrf
    <input type="email" name="email" placeholder="Email" required>

    <button type="submit">Resend Verification Email</button>
</form>
