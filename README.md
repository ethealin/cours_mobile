# API Documention

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
PUT /v1/users/@id 
```
It needs the id parameter and the administrator token : $access_token. Others parameters are optionals : $login, $password, $admin, $email, $token
```
DELETE /v1/users/@id 
```
It needs the id parameter and the administrator token : $access_token.
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
POST /v1/films/watched 
```
It needs $id_users and $id_films with POST method AND the related user's $access_token with GET method.
```
DELETE /v1/films/watched/@id_film 
```
it needs the @id_film parameter and the related user's $access_token with GET method.
```
POST /v1/films/liked 
```
It needs $id_users and $id_films with POST method AND the related user's $access_token with GET method.
```
DELETE /v1/films/liked/@id_film 
```
it needs the @id_film parameter and the related user's $access_token with GET method.
```
POST /v1/films/watch 
```
It needs $id_users and $id_films with POST method AND the related user's $access_token with GET method.
```
DELETE /v1/films/watch/@id_film 
```
it needs the @id_film parameter and the related user's $access_token with GET method.