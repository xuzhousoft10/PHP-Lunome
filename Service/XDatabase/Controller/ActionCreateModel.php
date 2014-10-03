<?php
/**
 * This file implemented the action to create model.
 */
namespace X\Service\XDatabase\Controller;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/**
 * The action handler class for creating database table model.
 * 
 * @author Michael Luthor <michaelluthor@163.com>
 */
class ActionCreateModel {
    /**
     * 
     * @param unknown $table
     * @param unknown $module
     */
    public function run( $table, $module ) {
        $moduleName = $module;
        $tableName = $table;
        /* $var $module \X\Core\Module\XModule */
        $module = X::system()->getModuleManager()->get($module);
        $modelPath = $module->getModulePath('Model');
        if ( !is_dir($modelPath) ) {
            mkdir($modelPath);
        }
        
        $columns = array();
        $columnInformation = TableManager::open($tableName)->getInformation();
        
        $content = array();
        $content[] = '<?php';
        $content[] = sprintf('namespace X\\Module\\%s\\Model;', $moduleName);
        $content[] = '/**';
        foreach ( $columnInformation as $column ) {
            $content[] = sprintf(' * @property string $%s', $column['Field']);
        }
        $content[] = ' **/';
        $content[] = sprintf('class %sModel extends \\X\\Service\\XDatabase\\Core\\ActiveRecord\\XActiveRecord {', $moduleName);
        $content[] = '    protected function describe() {';
        $content[] = '        $columns = array();';
        foreach ( $columnInformation as $column ) {
            $describe = array();
            $describe[] = $column['Type'];
            if ( 'NO' === $column['Null'] ) { $describe[] = 'NN'; }
            if ( 'PRI' === $column['Key'] ) { $describe[] = 'PK'; }
            if ( !empty($column['Default']) ) { $describe[] = sprintf('"%s"', $column['Default']); }
            $describe = implode(' ', $describe);
        
            $content[] = sprintf('        $columns[\'%s\']=\'%s\';', $column['Field'], $describe);
        }
        $content[] = '        return $columns;';
        $content[] = '    }';
        $content[] = '    protected function getTableName() {';
        $content[] = sprintf('        return \'%s\';', $tableName);
        $content[] = '    }';
        $content[] = '}';
        $content = implode("\n", $content);
        
        $modelPath = $module->getModulePath("Model/{$moduleName}Model.php");
        file_put_contents($modelPath, $content);
    }
}