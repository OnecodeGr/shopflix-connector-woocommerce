<?php

namespace Spyrmp\JsonSerializerDeserializer;



class Json
{
    /**
     * Serialize array to json
     * @param $data
     * @return string
     */
    static public function serialize($data)
    {
        $result = json_encode($data);
        if (false === $result) {
            throw new \Exception("Unable to serialize value. Error: " . json_last_error_msg());
        }
        return $result;
    }


    /**
     * Deserialize Json to array
     * @param $string
     * @return mixed
     */
    static public function deserialize($string)
    {
        $result = json_decode($string, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Unable to unserialize value. Error: " . json_last_error_msg());
        }
        return $result;
    }
}