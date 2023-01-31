# user_api

## Методы

### POST /users/ - создание пользователя
 В теле принимает json-объект с информацией о пользователе:
 ```javascript
{
"login":"John@mail.com",
"password":"very_strong_pwd"
}
```

 - login - логин пользователя
 - password - пароль
 
 При успешном создании возвращает json-объект с идентификатором созданного пользователя:
 
 ```javascript
{"created_user_id":"18"}
```

 В том случае, если пользователь с данным логином существует, вернется json-объект с ошибкой:
 ```javascript
{"error":"User already exists"}
```

### PUT /users/{id} - обновление данных пользователя
 В теле принимает json-объект с информацией о пользователе:
 ```javascript
{
"login":"John@mail.com",
"password":"new_very_strong_pwd",
"old_password": "very_strong_pwd"
}
```

 - login - (новый) логин пользователя
 - password - новый пароль
 - old_password - старый пароль
 
 При успешном создании возвращает json-объект с новой информацией о пользователе:
 
 ```javascript
{
"status":true,
"updated_user": {
    "id":"19",
    "login":"login@mail.ru",
    "password":"$2y$10$hOcOesrpCbr4Wc7cLdSbceDA6xv.bwcIG\/tqjK2o6eXCvm0m9uHSW"
    }
}
```

 В том случае несоответствия старого пароля вернет json-объект с ошибкой:
 ```javascript
{"status":false,"error":"Incorrect old password"}
```


### DELETE /users/{id} - удаление пользователя с идентификатором id
 Возвращает json-объект с количеством удаленных пользователей:
 ```javascript
{"deleted_users_count":1}
```


### POST /login/ - авторизация
 Возвращает json-объект со статусом и id пользователя:
 ```javascript
{"status":true,"user_id":"19"}
```

В случае неуспешной авторизации возвращает json-объект со статусом и информацией об ошибке:

- некорректный пароль:

 ```javascript
{"status":false,"error":"Incorrect password"}
 ```
 
 - некорректный логин:

 ```javascript
{"status":false,"error":"Incorrect login"}
 ```


### GET /users/{id} - получение информации о пользователе с идентификатором id
 Возвращает json-объект с информацией о пользователе:
 ```javascript
{
"id":"1",
"login":"John@mail.com",
"password":"2y$10$cT89azaBQpezzUid942J2eV9bUf.BBp2KKpW99ARzGzf5myq\/lTEy"
}
```

 - id - идентификатор
 - login - логин пользователя
 - password - hash-code пароля
 
 ### GET /users/ - получение пользователей
 Возвращает json-массив с объектами пользователей

