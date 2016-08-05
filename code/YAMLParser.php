<?php

namespace UncleCheese\SerialisedDBFields;

use Symfony\Component\Yaml\Yaml;

class YAMLParser implements SerialisedDBFieldParser
{
	public function parse($data)
	{
		return Yaml::parse($data);
	}
}