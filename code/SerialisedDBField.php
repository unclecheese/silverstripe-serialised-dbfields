<?php

namespace UncleCheese\SerialisedDBFields;

use \Text;
use \DBField;
use \FormField;
use \ArrayLib;
use \ArrayList;

abstract class SerialisedDBField extends Text
{
	
	protected $unserialisedData;

	
	protected $parser;

	
	protected $editorField;

	
	public function __construct($name = null, $unserialisedData = null)
	{	
		$this->unserialisedData = $unserialisedData;

		parent::__construct($name);
	}

		
	public function hasField($field)
	{	
		$data = $this->getUnserialisedData();
		
		return isset($data[$field]);
	}

	
	public function getField($field)
	{
		return $this->obj($field);
	}


	public function obj($fieldName, $arguments = null, $forceReturnedObject = true, $cache = false, $cacheName = null) 
	{
		$data = $this->getFieldFromData($fieldName);

		if($data === null) return null;
		
		if(is_array($data)) {			
			if(ArrayLib::is_associative($data)) {				
				$newField = static::create($this->name, $data);
				$newField->setValue($this->getValue());
			}
			else {				
				$newField = ArrayList::create();
				foreach($data as $item) {
					$newField->push(
						static::create($this->name, $item)
					);
				}
			}
			return $newField;
		}

		return DBField::create_field('Text', $data);
	}


	public function scaffoldFormField($title = null, $params = null) 
	{
		return $this->editorField
			->setName($this->name)
			->setTitle($title);
	}


	public function setParser(SerialisedDBFieldParser $parser)
	{
		$this->parser = $parser;
	}

	
	public function setEditor(FormField $field)
	{
		$this->editorField = $field;
	}
	

	public function getUnserialisedData()
	{
		if($this->unserialisedData) return $this->unserialisedData;

		return $this->unserialisedData = $this->parser->parse($this->getValue());
	}

	
	protected function getFieldFromData($field)
	{
		$data = $this->getUnserialisedData();

		if(!isset($data[$field])) return null;
		
		return $data[$field];
	}


}