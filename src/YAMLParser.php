<?php

namespace UncleCheese\SerialisedDBFields;

use Symfony\Component\Yaml\Yaml;

/**
 * Class YAMLParser
 * @package UncleCheese\SerialisedDBFields
 */
class YAMLParser implements SerialisedDBFieldParser
{
    /**
     * @param $data
     * @return array
     */
    public function parse($data)
    {
        return Yaml::parse($data);
    }
}