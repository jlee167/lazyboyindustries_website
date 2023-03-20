** Writing API list.  API 리스트 작성중


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
