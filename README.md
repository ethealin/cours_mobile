# API Documention

## Notifications
- I have added 4 lines ( lines 979 to 983 ) in "framework/base.php" to catch the 404 error and send a response using Api class.
- I have done many tests to protect a little bit this API but I didn't finish yet. That's why sometimes some methods are more restrictive than others.

## Endpoints

```
GET /v1/login	
```
It needs $email and $password with GET method as string.

```
POST /v1/subscribe 
```
It needs $email , $password , $login , $access_token with POST method as string . $admin ( int ) is optional. If he is there, the user will be administrator.
```
GET /v1/users 
```
It needs $access_token with GET method.
```
GET /v1/users/@id
```
It need @id parameters ( user's id ) and an administrator $access_token with GET method
```
PUT /v1/users/@id 
```
It needs the id parameter and the administrator token : $access_token ( in GET method ). Others parameters are optionals : $login, $password, $admin, $email, $token
```
DELETE /v1/users/@id 
```
It needs the id parameter and the administrator token : $access_token( in GET method ).
```
GET /v1/films 
```
It doesn't need any parameters.
```
GET /v1/films/@id 
```
It only needs the id parameter.
```
POST /v1/films 
```
It needs $access_token ( for admin ) with GET method and $title , $abstract with POST method.
```
PUT /v1/films/@id 
```
It need the @id parameter and an admin $access_token with PUT method. Others parameters are optionals : $login, $password, $admin, $email, $token
```
DELETE /v1/films/@id 
```
It needs the id parameter and the administrator token : $access_token.
```
GET /v1/films/watched
```
It needs nothing.
```
GET /v1/films/watched/@id
```
It only needs @id parameter
```
POST /v1/films/watched 
```
It needs $id_users and $id_films with POST method AND the related user's $access_token with GET method.
```
DELETE /v1/films/watched/@id_film 
```
it needs the @id_film parameter and the related user's $access_token with GET method.
```
GET /v1/films/liked
```
It needs nothing.
```
GET /v1/films/liked/@id
```
It only needs @id parameter
```
POST /v1/films/liked 
```
It needs $id_users and $id_films with POST method AND the related user's $access_token with GET method.
```
DELETE /v1/films/liked/@id_film 
```
it needs the @id_film parameter and the related user's $access_token with GET method.
```
GET /v1/films/watch
```
It needs nothing.
```
GET /v1/films/watch/@id
```
It only needs @id parameter
```
POST /v1/films/watch 
```
It needs $id_users and $id_films with POST method AND the related user's $access_token with GET method.
```
DELETE /v1/films/watch/@id_film 
```
it needs the @id_film parameter and the related user's $access_token with GET method.