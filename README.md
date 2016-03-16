# weblibs-dbabstraction
weblibs-dbabstraction is an Abstraction library for the database and ORM modules.

## Description of the main components
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ba8db8b9-1af7-471b-965e-d055f23b6dce/big.png)](https://insight.sensiolabs.com/projects/ba8db8b9-1af7-471b-965e-d055f23b6dce)

### Entity
Is an object which expose properties dynamically generated from an array of fields.
```php
new Entity(FieldsArray)
```

### Adapter
Is a persistence layer which interact with database or other backends.
```php
new Adapter(parameters...)
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
