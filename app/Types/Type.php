<?php
/**
 * User: Demetrio.Vray
 * Date: 08/24/18
 */
namespace App\Types;

abstract class Type{
    /**
     *  Get Constants of the called class
     * @return mixed
     */
    protected static function export(){
        $class = new \ReflectionClass(get_called_class());
        $property = $class->getProperty('labels');
        $property->setAccessible(true);
        $label = $property->getValue(get_called_class());
        $list = [];
        foreach($class->getConstants() as $key => $value){
            $output = isset($label[$key]) ? $label[$key] : ucwords(str_replace('_',' ',strtolower($key)));
            $list[] = [
                'label' => __("label.$output"),
                'value' => $value
            ];
        }
        return $list;
    }

    /**
     * Return enums to array
     * @return mixed
     */
    public static function toArray(){
        return static::export();
    }

    /**
     * Return enums to json
     * @return array
     */
    public static function toJson(){
        return json_encode(static::export());
    }
}