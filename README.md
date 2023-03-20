<h1>API List (Incomplete) /
API 리스트 (작성중)</h1>

<h3>User Management</h3>

|    Endpoint   | HTTP Request  | Request Body | Description|
| ------------- | ------------- | ------------- |------------- |
| /ping         | GET  | **TBD | Get ping from the server |
| /auth         | POST  | **TBD | Normal login with ID and Password|
| /auth/kakao         | POST  | **TBD | Request Kakao OAuth authentication |
| /auth/google         | POST  | **TBD | Request Google OAuth authentication |
| /auth/2fa | POST | **TBD | Authenticate with Google OTP |
| /members/2fa-key         | GET  | **TBD | Enable 2FA feature and get 2FA QR Code |
| /members/2fa-key         | DELETE  | **TBD | Disable 2FA feature |
| /members/password         | PUT  | **TBD | Change password |
| /email/resend | GET | **TBD | Resend account verification email |
| /email/verify/{id}/{hash} | GET | **TBD | Confirm email verification and return user to main page |
| /forgot-password | GET | **TBD | Redirect to password recovery page |
| /forgot-password | POST | **TBD | Send password recovery email to user |
| /reset-password | GET | **TBD | Reset password of user in password recovery process |
| /self/ | GET | **TBD | Get current user's information |
| /self/webtoken | GET | **TBD | Get JWT token for current user's own stream |
| /self/webtoken | POST | **TBD | Renew JWT token for current user's own stream |
| /self/profile_image        | GET  | **TBD | Get user's profile image URL |
| /self/uid        | GET  | **TBD | Get current user's ID number |
| /{stream_id}/webtoken | GET | **TBD | Get JWT token for specified stream if the user is a protector of streamer |
| /members/{username}        | GET  | **TBD | Get user info from username |
| /members/{username}        | POST  | **TBD | Register a new user |
| /members/        | PUT  | **TBD | Update user information |
| /members/        | DELETE  | **TBD | Delete a user from database |

<h3>Forum</h3>

|    Endpoint   | HTTP Request  | Request Body | Description|
| ------------- | ------------- | ------------- |------------- |
| /forum/{forum_name}/post/{post_id}   | GET  | **TBD | Retrieve a post by id |
| /forum/{forum_name}/post/   | POST  | **TBD | Register a new post written by current user |
| /forum/{forum_name}/post/{post_id}   | PUT  | **TBD | Update the contents a post |
| /forum/{forum_name}/post/{post_id}   | DELETE  | **TBD | Delete a post if current user is the author |
| /forum/{forum_name}/post/{post_id}/like   | POST  | **TBD | Current user likes a post |
| /forum/{forum_name}/page/{page}/{keyword?}   | GET  | **TBD | Returns a page of forum posts specified by page and search keyword |
| /forum/{forum_name}/page/{page}/tag/{tag}   | GET  | **TBD | Returns a page of forum posts specified by a tag |
| /forum/all_forums/top_posts   | GET  | **TBD | Returns 10 most viewed posts of all time |
| /forum/all_forums/trending_posts   | GET  | **TBD | Returns 10 most viewed posts of this week |
| /forum/comment/{comment_id}   | GET  | **TBD | Returns a single comment specified by comment ID |
| /forum/comment   | POST  | **TBD | Register a new comment |
| /forum/comment/{comment_id}   | PUT  | **TBD | Update the contents of a comment |
| /forum/comment/{comment_id}   | DELETE  | **TBD | Deletes a comment by comment ID |

