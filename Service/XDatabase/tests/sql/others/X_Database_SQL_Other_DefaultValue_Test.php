<?php
/**
 * SQLBuilderDefaultValueTest.php
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */

/**
 * SQLBuilderDefaultValue test case.
 */
class X_Database_SQL_Other_DefaultValue_Test extends PHPUnit_Framework_TestCase {
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp ();
    }
    
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        parent::tearDown ();
    }
    
    /**
     * testSQLBuilderDefaultValue
     */
    public function testSQLBuilderDefaultValue() {
        $this->assertInstanceOf('X\\Database\\SQL\\Other\\DefaultValue', new X\Database\SQL\Other\DefaultValue());
    }
}

