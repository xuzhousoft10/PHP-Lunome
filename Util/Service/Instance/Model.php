<?php
namespace X\Util\Service\Instance;
/**
 * 
 */
abstract class Model extends Basic {
    /**
     * @var \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord
     */
    protected $model = null;
    
    /**
     * @return \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord
     */
    public static function getMeta( ) {
        $class = static::getModelClass();
        return $class::model();
    }
    
    /**
     * @return string
     */
    protected static function getModelClass() {
        return null;
    }
}