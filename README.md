# weblibs-dbabstraction

weblibs-dbabstraction is an abstraction library for the PHP-gettext backend.

*Copyright (C) 2015 Claudio Giordano <claudio.giordano@autistici.org>*



## Entity
### Is a collection of fields
    new Entity(FieldsArray)

## Adapter
### Is an abstraction level to interact with persistence
    new Adapter(parameters...)

## Mapper
### Glue between Entity and Adapter
    new Mapper(Adapter, EntityOptionsArray)

## Doctrine vs dbabstraction structure comparision
### Entity          Entity
### DbLinkedApi     Mapper
### Adapter         Adapter
