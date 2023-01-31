# user_api

## Методы



### GET /users/{id} - получение информации о пользователе с идентификатором id
 - возвращает json-объект с информацией о пользователе:
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
