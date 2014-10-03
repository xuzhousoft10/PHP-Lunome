<?php
/**
 * The setting Controller file for XDatabase service.
 */
namespace X\Service\XDatabase\Controller;

/**
 * Use statements.
 */
use X\Core\X;
use X\Service\XDatabase\Core\Exception;

/**
 * The controller class.
 */
class SettingController extends \X\Core\Service\SettingController {
    /**
     * Create a db migration for given module.
     * 
     * @param unknown $module
     * @param unknown $table
     */
    public function actionCreateMigration( $table, $module ) {
        try {
            $handler = new ActionCreateMigration();
            return $handler->run($table, $module );
        } catch ( Exception $e ) {
            echo $e->getMessage();
        }
    }
    
    /**
     * 
     */
    public function actionCreateModel( $table, $module ) {
        try {
            $handler = new ActionCreateModel();
            $handler->run($table, $module);
        } catch ( Exception $e ) {
            echo $e->getMessage();
        }
    }
}