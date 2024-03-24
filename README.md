Personal website with demo features for personal projects. <br><br><br>


![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E)
![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?style=for-the-badge&logo=vuedotjs&logoColor=%234FC08D)



<h4> Current PHP version: 8.0 </h4></br></br></br>


<h2>API List (Incomplete) </h2>

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
<br>

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
<br>

<h3>Guardianship Management</h3>

|    Endpoint   | HTTP Request  | Request Body | Description|
| ------------- | ------------- | ------------- |------------- |
| /members/guardian/all   | GET  | **TBD | Returns the guardians of current user |
| /members/guardian/{username}   | POST  | **TBD | Request another user to be current user's guardian |
| /members/guardian/{uid}   | DELETE  | **TBD | Remove a guardian from the guardian list |
| /members/protected/all   | GET  | **TBD | Returns the protecteds of current user |
| /members/protected/{username}   | POST  | **TBD | Request another user to be current user's protected |
| /members/protected/{uid}   | DELETE  | **TBD | Remove a protected from the protecteds list |
<br>

<h3>Commerce API</h3>
**TBD
