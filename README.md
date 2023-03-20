API List (Incomplete)


|    Endpoint   | HTTP Request  | Request Body | Description|
| ------------- | ------------- | ------------- |------------- |
| /auth         | POST  | **TBD | Normal login with ID and Password|
| /auth/kakao         | POST  | **TBD | OAuth Login with Kakao account|
| /auth/google         | POST  | **TBD | OAuth Login with Google account|
| /auth/2fa | POST | **TBD | Authenticate with Google OTP |
| /members/2fa-key         | GET  | **TBD | Enable 2FA feature and get 2FA QR Code |
| /members/2fa-key         | DELETE  | **TBD | Disable 2FA feature |
| /members/password         | PUT  | **TBD | Change password |
| /email/resend | GET | **TBD | Resend account verification email |
| /email/verify/{id}/{hash} | GET | **TBD | Confirm email verification and return user to main page |
| /forgot-password | GET | **TBD | Redirect to password recovery page |
| /forgot-password | POST | **TBD | Send password recovery email to user |
| /reset-password | GET | **TBD | Reset password of user in password recovery process |
| /self/webtoken | GET | **TBD | Get JWT token for current user's own stream |
| /self/webtoken | POST | **TBD | Renew JWT token for current user's own stream |
| /{stream_id}/webtoken | GET | **TBD | Get JWT token for specified stream if the user is a protector of streamer |
