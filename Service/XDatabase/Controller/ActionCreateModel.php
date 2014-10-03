<?php
/**
 * This file implemented the action to create model.
 */
namespace X\Service\XDatabase\Controller;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Core\Table\Manager as TableManager;

/**
 * The action handler class for creating database table model.
 * 
 * @author Michael Luthor <michaelluthor@163.com>
 */
class ActionCreateModel {
    /**
     * Execute the creation, if the table name is 'all', then all the tables
     * would be generated a model.
     * 
     * @param string $table The name of table to generate.
     * @param string $module The name of module to store the model.
     */
    public function run( $table, $module ) {
        $tables = array($table);
        if ( 'ALL' === strtoupper($table) ) {
            $tables = Manager::getTables();
        }
        foreach ( $tables as $table ) {
            $this->generateModel($table, $module);
        }
    }
    
    /**
     * Generate the model file by given table an module name.
     * 
     * @param string $table The name of table to generate.
     * @param string $module The name of module to store the model.
     */
    private function generateModel( $table, $module ) {
        $moduleName = $module;
        $tableName = $table;
        
        /* Create model path for module */
        /* $var $module \X\Core\Module\XModule */
        $module = X::system()->getModuleManager()->get($module);
        $modelPath = $module->getModulePath('Model');
        if ( !is_dir($modelPath) ) {
            mkdir($modelPath);
        }
        
        /* Generate the model content. */
        $modelClassName = ucfirst($this->convertSnakeToCamel($table));
        $columnInformation = TableManager::open($tableName)->getInformation();
        $content = array();
        $content[] = "<?php";
        $content[] = "namespace X\\Module\\$moduleName\\Model;";
        $content[] = '';
        $content[] = '/**';
        $content[] = ' * Use statements';
        $content[] = ' */';
        $content[] = 'use X\\Service\\XDatabase\\Core\\Table\\ColumnType;';
        $content[] = 'use X\\Service\\XDatabase\\Core\\ActiveRecord\\Column;';
        $content[] = '';
        $content[] = '/**';
        /* Add property comments */
        foreach ( $columnInformation as $column ) {
            $content[] = " * @property string \${$column['Field']}";
        }
        $content[] = ' **/';
        $content[] = "class {$modelClassName}Model extends \\X\\Service\\XDatabase\\Core\\ActiveRecord\\XActiveRecord {";
        $content[] = "    /**\n     * (non-PHPdoc)\n     * @see \\X\\Service\\XDatabase\\Core\\ActiveRecord\\XActiveRecord::describe()\n     */";
        $content[] = "    protected function describe() {";
        $content[] = "        \$columns = array();";
        /* Generate the column definations */
        foreach ( $columnInformation as $column ) {
            $name               = $column['Field'];
            $type               = explode('(', $column['Type']);
            $type               = explode(' ', $type[0]);
            $typeUpper          = strtoupper($type[0]);
            $typeLower          = strtolower($type[0]);
            $length             = explode('(', $column['Type']);
            $length             = isset($length[1]) ? explode(')', $length[1]) : null;
            $length             = is_null($length) ? null : $length[0];
            $isUnsigned         = false !== strpos($column['Type'], 'unsigned');
            $isZerofill         = false !== strpos($column['Type'], 'zerofill');
            $isNullable         = 'NO' !== $column['Null'];
            $isPrimaryKey       = 'PRI' === $column['Key'];
            $isAutoIncrement    = 'auto_increment' === $column['Extra'];
            $default            = $column['Default'];
            

            $lengthRequired = array('VARCHAR', 'CHAR');
            
            $describe  = "        \$columns[] = Column::create('$name')";
            $describe .= "->setType(ColumnType::T_$typeUpper)";
            if ( in_array($typeUpper, $lengthRequired) ) {
                $describe .= "->setLength($length)";
            }
            if ( $isPrimaryKey ) {
                $describe .= '->setIsPrimaryKey(true)';
            }
            if ( $isUnsigned ) {
                $describe .= '->setIsUnsigned(true)';
            }
            if ( $isZerofill ) {
                $describe .= '->setIsZeroFill(true)';
            }
            if ( !$isNullable ) {
                $describe .= '->setNullable(false)';
            }
            if ( $isAutoIncrement ) {
                $describe .= '->setIsAutoIncrement(true)';
            }
            if ( !is_null($default) ) {
                $default = addslashes($default);
                $describe .= "->setDefault('$default')";
            }
            $describe .= ";";
            $content[] = $describe;
        }
        $content[] = '        return $columns;';
        $content[] = '    }';
        $content[] = "";
        /* generate the table name */
        $content[] = "    /**\n     * (non-PHPdoc)\n     * @see \\X\\Service\\XDatabase\\Core\\ActiveRecord\\XActiveRecord::getTableName()\n     */";
        $content[] = '    protected function getTableName() {';
        $content[] = "        return '$tableName';";
        $content[] = '    }';
        $content[] = '}';
        $content = implode("\n", $content);
        
        /* Save the model content */
        $modelPath = $module->getModulePath("Model/{$modelClassName}Model.php");
        file_put_contents($modelPath, $content);
    }
    
    /**
     * Convert string from snake style to camel style.
     * 
     * @param string $snake The snake string.
     * @return string
     */
    private function convertSnakeToCamel( $snake ) {
        $length = strlen($snake);
        $snake = str_split($snake);
        $camel = array();
        
        for ( $i=0; $i<$length; $i++ ) {
            if ( '_' === $snake[$i] ) {
                $snake[$i+1] = strtoupper($snake[$i+1]);
            } else {
                $camel[] = $snake[$i];
            }
        }
        return implode('', $camel);
    }
}