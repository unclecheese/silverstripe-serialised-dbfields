<?php

namespace UncleCheese\SerialisedDBFields;

/**
 * Class CodeEditorFactory
 * @package UncleCheese\SerialisedDBFields
 */
class CodeEditorFactory implements \SilverStripe\Framework\Injector\Factory
{

    /**
     * @param $service
     * @param array $params
     * @return FormField
     */
    public function create($service, array $params = [])
    {
        if (class_exists('\CodeEditorField')) {
            return \CodeEditorField::create('')
                ->setMode($params[0]);
        }

        return \TextareaField::create('')
            ->setRows(30);
    }
}
