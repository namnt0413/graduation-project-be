@component('mail::message')
# Forgot Password Mail

Click vao nut ben duoi de cap nhat lai mat khau moi!

@component('mail::button', ['url' => 'http://localhost:3000/response-password-reset?token='.$token])
Reset Password
@endcomponent

@endcomponent
