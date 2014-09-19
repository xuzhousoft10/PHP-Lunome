<?php
/**
 * ActiveRecordColumnTest.php
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version $Id$
 */
use X\Database\ActiveRecord\Column;
use X\Database\Test\Resources\ActiveRecordColumnTester;
/**
 * 
 */
require_once TEST_RESOURCE_PATH.'active.records/active.record.column.tester.php';
/**
 * ActiveRecordColumn test case.
 */
class X_Database_ActiveRecord_Column_Test extends PHPUnit_Framework_TestCase {
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
     * Tests ActiveRecordColumn->__construct()
     */
    public function test__construct() {
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT');
        $column->setValue('string');
        $this->assertFalse($column->validate());
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT NN');
        $this->assertFalse($column->validate());
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT UN');
        $column->setValue(-1);
        $this->assertFalse($column->validate());
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'PK INT');
        $this->assertTrue($column->getIsPrimaryKey());
        $this->assertFalse($column->validate());
        $this->assertGreaterThan(0, count($column->getErrors()));
    }
    
    /**
     * 
     */
    public function test__clone() {
        $column = new Column(new ActiveRecordColumnTester(), 'column', 'INT');
        $newColumn = clone $column;
        $this->assertInstanceOf('X\Database\ActiveRecord\Column', $newColumn);
    }
    
    /**
     * 
     */
    public function testSetRecord() {
        $column = new Column(new ActiveRecordColumnTester(), 'column', 'INT');
        $newColumn = clone $column;
        $newColumn->setRecord(new ActiveRecordColumnTester());
        $this->assertInstanceOf('X\Database\ActiveRecord\Column', $newColumn);
    }
    
    /**
     * Tests ActiveRecordColumn->setMinLength()
     */
    public function testSetMinLength() {
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'VARCHAR');
        $column->setMinLength(10);
        $column->setValue('xxxx');
        $this->assertFalse($column->validate());
        
        $column->setValue('1234567890');
        $this->assertTrue($column->validate());
    }
    
    /**
     * Tests ActiveRecordColumn->setEmptiable()
     */
    public function testSetEmptiable() {
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'VARCHAR');
        $column->setEmptiable(false);
        $column->setValue('');
        $this->assertFalse($column->validate());
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'VARCHAR');
        $column->setEmptiable(true);
        $column->setValue('');
        $this->assertTrue($column->validate());
    }
    
    /**
     * Tests ActiveRecordColumn->getIsPrimaryKey()
     */
    public function testGetIsPrimaryKey() {
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'VARCHAR');
        $this->assertFalse($column->getIsPrimaryKey());
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'PK VARCHAR');
        $this->assertTrue($column->getIsPrimaryKey());
    }
    
    /**
     * Tests ActiveRecordColumn->setValue()
     */
    public function testSetValue() {
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'VARCHAR');
        $column->setValue('string');
        $this->assertEquals('string', $column->getValue());
    }
    
    /**
     * Tests ActiveRecordColumn->getValue()
     */
    public function testGetValue() {
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'VARCHAR');
        $column->setValue('string');
        $this->assertEquals("'string'", $column->getValue(true));
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'VARCHAR');
        $column->setValue('string');
        $this->assertEquals('string', $column->getValue());
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'VARCHAR "default-value"');
        $this->assertEquals('default-value', $column->getValue());
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT AI');
        $this->assertEquals("''", $column->getValue(true));
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT "100"');
        $this->assertEquals("'100'", $column->getValue(true));
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT');
        $this->assertEquals("NULL", $column->getValue(true));
    }
    
    /**
     * Tests ActiveRecordColumn->isDirty()
     */
    public function testIsDirty() {
        $record = new ActiveRecordColumnTester();
        $column = new X\Database\ActiveRecord\Column($record, 'column', 'VARCHAR');
        $column->setValue('old-value');
        $record->setIsNew(false);
        $column->setValue('new-value');
        $this->assertTrue($column->isDirty());
        
        $record = new ActiveRecordColumnTester();
        $column = new X\Database\ActiveRecord\Column($record, 'column', 'VARCHAR');
        $column->setValue('new-value');
        $this->assertFalse($column->isDirty());
    }
    
    /**
     * Tests ActiveRecordColumn->addError()
     */
    public function testAddError() {
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'VARCHAR');
        $column->addError('error-message');
        $this->assertEquals(1, count($column->getErrors()));
    }
    
    /**
     * Tests ActiveRecordColumn->hasError()
     */
    public function testHasError() {
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT');
        $column->addError('error-message');
        $this->assertTrue($column->hasError());
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT');
        $this->assertFalse($column->hasError());
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT');
        $column->validate();
        $this->assertFalse($column->hasError());
    }
    
    /**
     * Tests ActiveRecordColumn->getErrors()
     */
    public function testGetErrors() {
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT');
        $column->validate();
        $this->assertFalse($column->hasError());
        $this->assertEquals(0, count($column->getErrors()));
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT NN');
        $column->validate();
        $this->assertEquals(1, count($column->getErrors()));
    }
    
    /**
     * Tests ActiveRecordColumn->addValidator()
     */
    public function testAddValidator() {
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT');
        $column->setValue(1);
        $column->addValidator(function( X\Database\ActiveRecord\Column $column ) {
            if ( 0 == $column->getValue() ) {
                $column->addError('Value can not be 0.');
                return false;
            }
            return true;
        });
        $column->validate();
        $this->assertFalse($column->hasError());
        
        $column->setValue(0);
        $column->validate();
        $this->assertTrue($column->hasError());
    }
    
    /**
     * Tests ActiveRecordColumn->validate()
     */
    public function testValidate() {
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT NN');
        $column->validate();
        $this->assertEquals(1, count($column->getErrors()));
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'VARCHAR');
        $column->setValue(new stdClass());
        $column->validate();
        $this->assertEquals(1, count($column->getErrors()));
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'VARCHAR(1)');
        $column->setValue('12');
        $column->validate();
        $this->assertEquals(1, count($column->getErrors()));
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'DATETIME');
        $column->setValue('xxxx-xxxx');
        $column->validate();
        $this->assertEquals(1, count($column->getErrors()));
        
        $column = new X\Database\ActiveRecord\Column(new ActiveRecordColumnTester(), 'column', 'INT AI');
        $this->assertTrue($column->validate());
    }
    
    /**
     * 
     */
    public function testGetIsAutoIncrease() {
        $column = new Column(new ActiveRecordColumnTester(), 'column', 'INT NN');
        $this->assertFalse($column->getIsAutoIncrease());
        
        $column = new Column(new ActiveRecordColumnTester(), 'column', 'INT NN AI');
        $this->assertTrue($column->getIsAutoIncrease());
    }
    
    /**
     * 
     */
    public function testCleanDirty() {
        $record = new ActiveRecordColumnTester();
        $column = new Column($record, 'column', 'INT NN');
        $column->setValue('100');
        $record->setIsNew(false);
        $column->setValue('200');
        $column->cleanDirty();
        $this->assertEquals('100', $column->getValue());
    }
    
    /**
     * 
     */
    public function testRefresh() {
        $record = new ActiveRecordColumnTester();
        $column = new Column($record, 'column', 'INT NN');
        $column->setValue('100');
        $record->setIsNew(false);
        $column->setValue('200');
        $column->refresh();
        $this->assertEquals('200', $column->getValue());
        $this->assertFalse($column->isDirty());
    }
}