<?php

namespace Spyrmp\JsonSerializerDeserializer\Tests;

use Spyrmp\JsonSerializerDeserializer\Json;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{

    public function testSerialize()
    {
        $this->assertIsString(Json::serialize(["name" => "test_name", "lastname" => "test_lastname"]));
    }

    public function testDeserialize()
    {
        $this->assertIsArray(Json::deserialize('{"name":"test_name","lastname":"test_lastname"}'));
    }
}
