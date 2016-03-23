# weblibs-dbabstraction
weblibs-dbabstraction is an Abstraction library for the database and ORM modules.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ba8db8b9-1af7-471b-965e-d055f23b6dce/big.png)](https://insight.sensiolabs.com/projects/ba8db8b9-1af7-471b-965e-d055f23b6dce)

## Description of the main components

### Adapter description
Is a persistence layer which interact with database or other backends.
An adapter class must be implements the **DatabaseAdapterInterface** for compatibility with other components.<br />
The default adapter is the already defined **PDOAdapter** wich simplify the access to PDO object and related methods.<br />
Other specific adapters can be implemented to easily access to other backends.

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
An *entity* is an object which expose properties dynamically generated from an array of fields.
It is a simple class which have defined the magic methods (__set, __get ... ).<br />
The entity is automatically used by the *mapper* class for the operations and can be used to gets and sets its properties.<br />
*For more details please see the SampleEntity class into testdata folder.*

### Entity usage
An entity class must be extends **AbstractEntity** as:
```php
/**
 * Class SampleEntity
 */
class SampleEntity extends AbstractEntity
{
}
```

Then can be used:
```php
$entityClass->property = "value";
echo $entityClass->property;
```

### Mapper description
A mapper is a glue between **Entity** and **Adapter** objects which expose high level method to use and persists data.<br />
A mapper class must be extends the **AbstractMapper**:
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
protected $entityTable = 'sample_table';
protected $entityClass = 'SampleEntity';
```

therefore must be implements the abstract method **createEntity** for the correct mapping between table fields and the desidered entity properties:
```php
protected function createEntity(array $fields)
{
    return new SampleEntity(
        [
            'id' => $fields['id'],
            'code' => $fields['code'],
            'brand' => $fields['brand'],
            'model' => $fields['model'],
            'description' => $fields['description']
        ]
    );
}
```

You can also define additional methods if necessary or override existing ones such as insert, update, delete etc to modify its behavior.

### Mapper usage
TODO

## Legal
*Copyright (C) 2015 Claudio Giordano <claudio.giordano@autistici.org>*
