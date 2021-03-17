# API
Especificação das rotas

## Definições gerais
* O tipo de conteúdo das requisições e das respostas é JSON (application/json)

## Usuários

### Listar usuários

* GET `/api/user`

#### Resposta 200
```json
[{
    "id": 1,
    "name": "user 1",
    "email": "user1@email.com",
    "document": "88821597016",
    "balance": 170.32,
    "type_id": 1
}, {
    "id": 2,
    "name": "user 2",
    "email": "user2@email.com",
    "document": "26826837030",
    "balance": 0,
    "type_id": 1
}, {
    "id": 4,
    "name": "user 3",
    "email": "user3@email.com",
    "document": "77016358000137",
    "balance": 0,
    "type_id": 2
}]
```


### Criar usuário
* POST `/api/user`

#### Payload
```json
{
    "name": "user 1",
    "email": "user1@email.com",
    "document": "88821597016",
    "password": "pass123",
    "type_id": 1,
}
```

#### Resposta 201
```json
{
    "id": 1,
    "name": "user 1",
    "email": "user1@email.com",
    "document": "88821597016",
    "balance": 0,
    "type_id": 1
}
```

#### Resposta 422
```json
{   
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email has already been taken."
        ],
        "document": [
            "The document has already been taken."
        ]
    }
}
```

### Obter Usuário
* GET `/api/user/1`

#### Resposta 200
```json
{
    "id": 1,
    "name": "user 1",
    "email": "user1@email.com",
    "document": "88821597016",
    "type_id": 1,
    "balance": 170.32
}
```

### Depositar
* POST `/api/user/1/deposit`
#### Payload
```json
{
    "value": 230.0
}
```

#### Resposta 201
```json
{
    "balance": 230.0
}
```

#### Resposta 422
```json
{   
    "message": "The given data was invalid.",
    "errors": {
        "value": [
            "The value must be at least 0.01."
        ]
    }
}
```


## Transferências

### Criar Transferência
* POST `/api/transaction`
#### Payload
```json
{
    "payer": 1,
    "payee": 2,
    "value": 37.0
}
```

#### Resposta 201
```json
{
    "id": 1,
    "payer_id": 1,
    "payee_id": 2,
    "value": 37.0
}
```

#### Resposta 422
```json
{   
    "message": "The given data was invalid.",
    "errors": {
        "value": [
            "The value must be at least 0.01."
        ]
    }
}
```
