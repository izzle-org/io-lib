<?php
namespace Izzle\IO\Utilities;

use InvalidArgumentException;
use stdClass;

class DataConverter
{
    /**
     * @param array|stdClass $data
     * @return array|stdClass
     */
    public static function convert($data)
    {
        if (!is_array($data) && !($data instanceof stdClass)) {
            throw new InvalidArgumentException('Parameter data must be an array or an instance of \\stdClass');
        }
        
        return is_array($data) ? self::toObject($data) : self::toArray($data);
    }
    
    /**
     * @param array $array
     * @return stdClass
     */
    private static function toObject(array $array): stdClass
    {
        if (function_exists('json_encode')) {
            return json_decode(json_encode($array));
        }
        
        foreach (array_keys($array) as $key) {
            if (is_array($array[$key])) {
                $array[$key] = self::toObject($array[$key]);
            }
        }
        
        return (object) $array;
    }
    
    /**
     * @param stdClass $object
     * @return array
     */
    private static function toArray(stdClass $object): array
    {
        if (function_exists('json_encode')) {
            return json_decode(json_encode($object), true);
        }
        
        foreach (array_keys(get_object_vars($object)) as $property) {
            if ($object->{$property} instanceof stdClass) {
                $object->{$property} = self::toArray($object->{$property});
            }
        }
        
        return (array) $object;
    }
}
