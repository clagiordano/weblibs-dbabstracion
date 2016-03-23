# weblibs-dbabstraction
weblibs-dbabstraction is an Abstraction library for the database and ORM modules.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ba8db8b9-1af7-471b-965e-d055f23b6dce/big.png)](https://insight.sensiolabs.com/projects/ba8db8b9-1af7-471b-965e-d055f23b6dce)

## Description of the main components


### Adapter
Is a persistence layer which interact with database or other backends.
The default adapter is PDOAdapter wich simplify the access to PDO object and related methods.

#### Usage
```php
new PDOAdapter(
    $dbHost,
    $dbUser,
    $dbPassword,
    $dbName,
    $dbDriver,
    $dbCharset,
    $isPersistent
);
```

See PDOAdapterTest class (phpunit test class) for full sample usage into tests folder.


### Entity
An entity is an object which expose properties dynamically generated from an array of fields.
```php
new Entity($fieldsArray);
```


### Mapper
Is a glue between Entity and Adapter objects which expose high level method to use and persist data.
```php
new Mapper(Adapter, EntityOptionsArray)
```

## Doctrine vs dbabstraction structure simple component comparision
| Doctrine      | dbAbstraction |
| ------------- | ------------- |
| Entity        | Entity        |
| DbLinkedApi   | Mapper        |
| Adapter       | Adapter       |

## Legal
*Copyright (C) 2015 Claudio Giordano <claudio.giordano@autistici.org>*
