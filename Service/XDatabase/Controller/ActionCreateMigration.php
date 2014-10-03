<?php
/**
 * This file implemented the handler class to create migrations.
 */
namespace X\Service\XDatabase\Controller;

/**
 * Use statement
 */
use X\Core\X;
use X\Service\XDatabase\Core\Table\Manager;

/**
 * This is the handler class for creating database migration.
 * 
 * @author Michael Luthor <michaelluthor@163.com>
 */
class ActionCreateMigration  {
    /**
     * Execute the creation. If $table is 'all', then all the tables in 
     * active database would be created. 
     * 
     * @param string $table The name of table or 'all'
     * @param string $module The name of module to store the migration.
     */
    public function run( $table, $module ) {
        $tables = array($table);
        if ( 'ALL' === strtoupper($table) ) {
            $tables = Manager::getTables();
        }
        foreach ( $tables as $table ) {
            $this->generateMigration($table, $module);
        }
    }
    
    /**
     * Generate the migration file for single table by given name.
     * 
     * @param string $table The name of table to created with.
     * @param string $module The name of module to store the migration.
     */
    private function generateMigration( $table, $module ) {
        /* Generate the original migration */
        $module = ucfirst($module);
        $migrationName = sprintf('create_db_table_%s', $table);
        $migrationPath = X::system()->getModuleManager()->migrateCreate($module, $migrationName);
        
        /* Load the migration into an array for using. */
        $migration = explode("\n", file_get_contents($migrationPath));
        
        /* Generate the new migration content */
        $dbMigration = array();
        foreach ( $migration as $line => $content ) {
            if ( "namespace X\\Module\\$module\\Migration;" === $content ) {
                /* Insert use statements */
                $dbMigration[] = $content;
                $dbMigration[] = '';
                $dbMigration[] = '/**';
                $dbMigration[] = ' * Use statements';
                $dbMigration[] = ' */';
                $dbMigration[] = 'use X\\Service\\XDatabase\\Core\\Table\\Column;';
                $dbMigration[] = 'use X\\Service\\XDatabase\\Core\\Table\\ColumnType;';
                $dbMigration[] = 'use X\\Service\\XDatabase\\Core\\Table\\Manager as TableManager;';
            } else if ( '        /*@TODO: Add your migration up code here.*/' === $content ) {
                /* Insert up code to create the table. */
                $content = $this->getMigrateUpCode($table);
                array_splice($dbMigration, count($dbMigration), 0, $content);
            } else if ( '        /*@TODO: Add your migration down code here. */' === $content ) {
                /* Insert down code to drop the table. */
                $dbMigration[] = "        TableManager::open('$table')->drop();";
            } else {
                /* Copy the migration content to new one. */
                $dbMigration[] = $content;
            }
        }
        
        $dbMigration = implode("\n", $dbMigration);
        file_put_contents($migrationPath, $dbMigration);
    }
    
    /**
     * Generate the migrate up code.
     * 
     * @param string $tableName The name of table.
     * @return array
     */
    private function getMigrateUpCode( $tableName ) {
        /* Insert up code to create the table. */
        $code[] = '        $columns = array();';
        
        $tablePrimaryKey = null;
        /* Attach column information */
        $tableInfo = Manager::open($tableName)->getInformation();
        foreach ( $tableInfo as $columnInfo ) {
            $name               = $columnInfo['Field'];
            $type               = explode('(', $columnInfo['Type']);
            $type               = explode(' ', $type[0]);
            $typeUpper          = strtoupper($type[0]);
            $typeLower          = strtolower($type[0]);
            $length             = explode('(', $columnInfo['Type']);
            $length             = isset($length[1]) ? explode(')', $length[1]) : null;
            $length             = is_null($length) ? null : $length[0];
            $isUnsigned         = false !== strpos($columnInfo['Type'], 'unsigned');
            $isZerofill         = false !== strpos($columnInfo['Type'], 'zerofill');
            $isNullable         = 'NO' !== $columnInfo['Null'];
            $isPrimaryKey       = 'PRI' === $columnInfo['Key'];
            $isAutoIncrement    = 'auto_increment' === $columnInfo['Extra'];
            $default            = $columnInfo['Default'];
            
            $lengthRequired = array('VARCHAR', 'CHAR');
            
            $column  = "        \$columns[] = Column::create('$name')";
            $column .= "->setType(ColumnType::T_$typeUpper)";
            if ( in_array($typeUpper, $lengthRequired) ) {
                $column .= "->setLength($length)";
            }
            if ( $isUnsigned ) {
                $column .= '->setIsUnsigned(true)';
            }
            if ( $isZerofill ) {
                $column .= '->setIsZeroFill(true)';
            }
            if ( !$isNullable ) {
                $column .= '->setNullable(false)';
            }
            if ( $isAutoIncrement ) {
                $column .= '->setIsAutoIncrement(true)';
            }
            if ( !is_null($default) ) {
                $default = addslashes($default);
                $column .= "->setDefault('$default')";
            }
            $column .= ";";
            
            if ( $isPrimaryKey ) {
                $tablePrimaryKey = $name;
            }
            
            $code[] = $column;
        }
        
        /* table's name could not contains special characters */
        if ( null === $tablePrimaryKey ) {
            $code[] = "        \$table = TableManager::create('$tableName', \$columns);";
        } else {
            $code[] = "        \$table = TableManager::create('$tableName', \$columns, '$tablePrimaryKey');";
            $code[] = "        \$table->addUnique('$tablePrimaryKey');";
        }
        
        return $code;
    }
}