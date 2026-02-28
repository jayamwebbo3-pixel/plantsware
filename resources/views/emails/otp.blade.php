<x-mail::message>
# Your OTP Verification Code

Hello,

You are receiving this email because you requested a login to your account. Please use the following One-Time Password (OTP) to complete your verification.

<x-mail::panel>
# {{ $otp }}
</x-mail::panel>

This OTP is valid for **5 minutes**. If you did not request this, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
