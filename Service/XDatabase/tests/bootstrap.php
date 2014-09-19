<?php
/**
 * bootstrap.php
 * 
 * The bootstrap file for phpunit.
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
namespace X\Database\Test;

/**
 * Import namespaces.
 */
use \X\Database\Management;

/**
 * Require needed files.
 */
require_once dirname(__FILE__).'/../management.php';

/**
 * The dsn string for testing mysql connection.
 * 
 * @var string
 */
define('TEST_MYSQL_DB_DSN', 'mysql:host=127.0.0.1;dbname=x_test_db');

/**
 * The username for testing mysql connection.
 * 
 * @var string
 */
define('TEST_MYSQL_DB_USERNAME', 'root');

/**
 * The password for testing mysql connection.
 * 
 * @var string
 */
define('TEST_MYSQL_DB_PASSWORD', '');

/**
 * The path of test resource folder.
 * 
 * @var string
 */
define('TEST_RESOURCE_PATH', sprintf('%s/resources/', dirname(__FILE__)));

/**
 * Add test db.
 */
if ( !Management::getManager()->hasDb() ) {
    Management::getManager()->addDb(Management::DEFAULT_DB_NAME, array(
        'dsn'       => TEST_MYSQL_DB_DSN, 
        'username'  => TEST_MYSQL_DB_USERNAME, 
        'password'  => TEST_MYSQL_DB_PASSWORD));
}