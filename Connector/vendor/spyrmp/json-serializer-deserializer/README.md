# Json Serializer/Deserializer for PHP

This library is serializing simple php arrays to json string also deserialize json to php arrays

## Install

This project can be installed via Composer run the following command:

```bash
composer require spyrmp/json-serializer-deserializer
```

## Usage

```php
$json = \Spyrmp\JsonSerializerDeserializer\Json::serialize(["name" => "test_name", "lastname" => "test_lastname"]);
$deserializedJson =  \Spyrmp\JsonSerializerDeserializer\Json::deserialize('{"name":"test_name","lastname":"test_lastname"}');
```
