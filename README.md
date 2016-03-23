# weblibs-dbabstraction
weblibs-dbabstraction is an Abstraction library for the database and ORM modules.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ba8db8b9-1af7-471b-965e-d055f23b6dce/big.png)](https://insight.sensiolabs.com/projects/ba8db8b9-1af7-471b-965e-d055f23b6dce)

## Description of the main components

### Adapter description
Is a persistence layer which interact with database or other backends.
The default adapter is PDOAdapter wich simplify the access to PDO object and related methods.

### Adapter usage
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

### Entity description
An entity is an object which expose properties dynamically generated from an array of fields.
It is a simple class wich has defined the magic methods (__set, __get ... ).
The entity is automatically used by the mapper class for operations and can be used to gets and sets its public properties.

### Entity usage
```php
$entityClass->property = "value";
echo $entityClass->property;
```

### Mapper description
Is a glue between Entity and Adapter objects which expose high level method to use and persists data.
A mapper class must be extends the AbstractMapper:
```php
/**
 * Class SampleMapper
 */
class SampleMapper extends AbstractMapper
{
```

then must be declare two protected properties to connect database table for persistence 
and the related entity class:
```php
protected $entityTable = 'tab_products';
protected $entityClass = 'SampleEntity';
```

### Mapper class example





## Doctrine vs dbabstraction structure simple component comparision
| Doctrine      | dbAbstraction |
| ------------- | ------------- |
| Entity        | Entity        |
| DbLinkedApi   | Mapper        |
| Adapter       | Adapter       |

## Legal
*Copyright (C) 2015 Claudio Giordano <claudio.giordano@autistici.org>*
