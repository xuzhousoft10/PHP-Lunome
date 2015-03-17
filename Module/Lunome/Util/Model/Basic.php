<?php
namespace X\Module\Lunome\Util\Model;
/**
 *
 */
abstract class Basic extends \X\Util\Model\Basic {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableNamePrefix()
     */
    protected function getTableNamePrefix() {
        return 'lunome_';
    }
}