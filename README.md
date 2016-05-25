#![ ](http://orig03.deviantart.net/d00b/f/2011/041/1/1/dodoo_animated_sprite_by_pokekoks-d397mvt.gif) Dodo Entity Serializer Library

Dodo is back and he's very angry (and hungry.. huh)! With him, you can now convert some entity-like objects into json and some json into entity-like object! And he automatically manages objects array and array-like json. So cute :)

---

## Table of Contents

* [EntitySerializer](#entityserializer)
    * [getInstance](#getinstance)
    * [jsonToObject](#jsontoobject)
    * [objectToJson](#objecttojson)
* [Coming Soon](#coming-soon)

## EntitySerializer

General class for DodoEntitySerializer. Provides all the methods to :
<ul>
<li> Convert Entity Object or Array of Entities Objects to JSON </li>
<li> Convert JSON to Entity Object or array of Entities Objects JSON </li>
</ul>



* Full name: \DodoPhpLab\DodoEntitySerializer\Classes\EntitySerializer


### getInstance



```php
EntitySerializer::getInstance(  )
```



* This method is **static**.



---

### jsonToObject

Returns an object from a json and the class's namespace of the object we want to return.

```php
EntitySerializer::jsonToObject( string $classNamespace, string $json ): mixed[]
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$classNamespace` | **string** | the class name with namespace. |
| `$json` | **string** | the json to transform in object. |


**Return Value:**

array of Entity-type objects or one Entity-type object.



---

### objectToJson

Returns a string formatted json from an object and his class's namespace.

```php
EntitySerializer::objectToJson( string $classNamespace, mixed[] $object ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$classNamespace` | **string** | the class name with namespace. |
| `$object` | **mixed[]** | the object to transform into json. |


**Return Value:**

the string formatted json representation of the $object passed in argument.



---

## ![ ](http://orig03.deviantart.net/d00b/f/2011/041/1/1/dodoo_animated_sprite_by_pokekoks-d397mvt.gif) Coming Soon

> - Handle add... methods (there : addSubscriber with User object in argument)
>
> - See how to manage null values
>
> - Manage exceptions
>
> - Write some things into README

--------
> This document was automatically generated from source code comments on 2016-05-25 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)