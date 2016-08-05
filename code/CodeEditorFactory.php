<?php

namespace UncleCheese\SerialisedDBFields;

class CodeEditorFactory implements \SilverStripe\Framework\Injector\Factory {

    public function create($service, array $params = array()) {    	
        if(class_exists('\CodeEditorField')) {
        	return \CodeEditorField::create('')
        		->setMode($params[0]);
        }

        return \TextareaField::create('')
        	->setRows(30);
    }
}
