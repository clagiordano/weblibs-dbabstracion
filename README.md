# weblibs-dbabstraction
weblibs-dbabstraction is an Abstraction library for the database and ORM modules.

# Description of the main components
---------

Entity
---------
Is an object which expose properties dynamically generated from an array of fields.
new Entity(FieldsArray)

Adapter
---------
Is a persistence layer which interact with database or other backends.
new Adapter(parameters...)

Mapper
---------
Is a glue between Entity and Adapter objects which expose high level method to use and persist data.
new Mapper(Adapter, EntityOptionsArray)

Doctrine vs dbabstraction structure comparision
---------
Entity          Entity
DbLinkedApi     Mapper
Adapter         Adapter

Legal
---------
*Copyright (C) 2015 Claudio Giordano <claudio.giordano@autistici.org>*
