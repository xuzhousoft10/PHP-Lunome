<?php
namespace X\Service\XDatabase\Test\ActiveRecord;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Test\Fixture\ActiveRecord\TestActiveRecord;
use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Core\Table\ColumnType;
/**
 * 
 */
class AttributeTest extends ServiceTestCase {
    /**
     * @var TestActiveRecord
     */
    private $activerecord = null;
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Test\Util\ServiceTestCase::setUp()
     */
    protected function setUp() {
        parent::setUp();
        $this->activerecord = new TestActiveRecord();
        
        $columns = array(
            'id'    => $this->activerecord->getAttribute('id')->toString(),
            'value' => $this->activerecord->getAttribute('value')->toString(),
            'status'=> $this->activerecord->getAttribute('status')->toString(),
            'mark'  => $this->activerecord->getAttribute('mark')->toString(),
        );
        Manager::create($this->activerecord->getTableFullName(), $columns);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\TestCase\ServiceTestCase::tearDown()
     */
    protected function tearDown() {
        Manager::open($this->activerecord->getTableFullName())->drop();
        $this->activerecord = null;
        parent::tearDown();
    }
    
    /**
     * 
     */
    public function test_length() {
//         $value = $this->activerecord->getAttribute('value');
        
//         $value->setMinLength(10);
//         $this->assertSame(10, $value->getMinLength());
//         $value->setValue('123456789');
//         $value->validate();
//         $this->assertTrue($value->hasError());
//         $value->setMinLength(0);
        
//         $value->setValue('0123456789012345678901234567890123456789');
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         $value->setValue('0123456789');
//         $value->validate();
//         $this->assertFalse($value->hasError());
        
//         $status = $this->activerecord->getAttribute('status');
//         $status->setMinLength(10);
//         $status->setValue('1');
//         $status->validate();
//         $this->assertFalse($status->hasError());
    }
    
//     /**
//      * 
//      */
//     public function test_unique() {
//         Manager::open($this->activerecord->getTableFullName())->insert(array(
//             'id'=>'1',
//             'value'=>'value1',
//             'status'=>'OK',
//             'mark'=>'mark1'
//         ));
        
//         $mark = $this->activerecord->getAttribute('mark');
//         $mark->setValue('mark1');
//         $mark->validate();
//         $this->assertTrue($mark->hasError());
        
//         $this->activerecord->setIsNew(false);
//         $mark->setOldValue('mark1');
//         $mark->setValue('mark1');
//         $mark->validate();
//         $this->assertFalse($mark->hasError());
//     }
    
//     /**
//      * 
//      */
//     public function test_value() {
//         $status = $this->activerecord->getAttribute('status');
        
//         $this->assertSame(1, (int)$status->getValue());
//         $status->setValue(null);
//         $status->setValueBuilder(function( $record, $attribute ) {
//             return get_class($record).'-'.$attribute;
//         });
//         $this->assertSame(get_class($this->activerecord).'-status', $status->getValue());
        
//         $status->setValue('new-value');
//         $this->assertTrue($status->getIsDirty());
//         $status->cleanDirty();
//         $this->assertFalse($status->getIsDirty());
//     }
    
//     /**
//      * 
//      */
//     public function test_validate_autoIncrement() {
//         $value = $this->activerecord->getAttribute('value');
        
//         /* if the column is auto increment, then there would be no validation. */
//         $value->setIsAutoIncrement(true);
//         $value->validate();
//         $this->assertFalse($value->hasError());
//         $value->setIsAutoIncrement(false);
//     }
    
//     /**
//      * 
//      */
//     public function test_validate_nullable() {
//         $value = $this->activerecord->getAttribute('value');
        
//         /* check nullable. */
//         $value->setValue(null);
//         $value->setNullable(false);
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         $value->setValue(null);
//         $value->setNullable(true);
//         $value->validate();
//         $this->assertFalse($value->hasError());
//     }
    
//     /**
//      * 
//      */
//     public function test_validate_data_type() {
//         $value = $this->activerecord->getAttribute('value');
//         $value->setType('non-exists-type');
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         /* check integer */
//         $value->setType(ColumnType::T_INT);
//         $value->setValue(1);
//         $value->validate();
//         $this->assertFalse($value->hasError());
        
//         $value->setValue('1');
//         $value->validate();
//         $this->assertFalse($value->hasError());
        
//         $value->setValue(1.1);
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         $value->setValue('1.1');
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         $value->setValue('string');
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         /* check tinyint */
//         $value->setType(ColumnType::T_TINYINT);
        
//         $value->setValue(1024);
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         $value->setValue(-1024);
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         $value->setValue(1);
//         $value->validate();
//         $this->assertFalse($value->hasError());
        
//         $value->setValue('1');
//         $value->validate();
//         $this->assertFalse($value->hasError());
        
//         $value->setValue('string');
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         /* check varchar */
//         $value->setType(ColumnType::T_VARCHAR);
        
//         $value->setValue(new \stdClass());
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         $value->setValue('');
//         $value->validate();
//         $this->assertFalse($value->hasError());
        
//         $value->setValue(1);
//         $value->validate();
//         $this->assertFalse($value->hasError());
        
//         /* check long text */
//         $value->setType(ColumnType::T_LONGTEXT);
        
//         $value->setValue(new \stdClass());
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         $value->setValue('');
//         $value->validate();
//         $this->assertFalse($value->hasError());
        
//         $value->setValue(1);
//         $value->validate();
//         $this->assertFalse($value->hasError());
        
//         /* check datetime */
//         $value->setType(ColumnType::T_DATETIME);
        
//         $value->setValue('2014-12-14 12:11:22');
//         $value->validate();
//         $this->assertFalse($value->hasError());
        
//         $value->setValue('invalidate-date-time');
//         $value->validate();
//         $this->assertTrue($value->hasError());
        
//         /* check date */
//         $value->setType(ColumnType::T_DATE);
        
//         $value->setValue('2014-12-14');
//         $value->validate();
//         $this->assertFalse($value->hasError());
        
//         $value->setValue('invalidate-date-time');
//         $value->validate();
//         $this->assertTrue($value->hasError());
//     }
    
//     /**
//      * 
//      */
//     public function test_validate_primary_key() {
//         Manager::open($this->activerecord->getTableFullName())->insert(array(
//         'id'=>'1',
//         'value'=>'value1',
//         'status'=>'OK',
//         'mark'=>'mark1'
//                 ));
        
//         $id = $this->activerecord->getAttribute('id');
//         $id->setValue('1');
//         $id->validate();
//         $this->assertTrue($id->hasError());
//     }
    
//     /**
//      * 
//      */
//     public function test_unsigned() {
//         $status = $this->activerecord->getAttribute('status');
        
//         $status->setValue(-1);
        
//         $status->setIsUnsigned(true);
//         $status->validate();
//         $this->assertTrue($status->hasError());
        
//         $status->setIsUnsigned(false);
//         $status->validate();
//         $this->assertFalse($status->hasError());
//     }
    
//     /**
//      * 
//      */
//     public function test_validator() {
//         $status = $this->activerecord->getAttribute('status');
//         $status->setValue(1);
//         $status->validate();
//         $this->assertFalse($status->hasError());
        
//         $status->addValidator(function($record, $attribute) {
//             if ( 1 === (int)$attribute->getValue() ) {
//                 $attribute->addError('Value could not be "1".');
//             }
//         });
//         $status->validate();
//         $this->assertTrue($status->hasError());
//         $errors = $status->getErrors();
//         $this->assertSame(1, count($errors));
//         $this->assertSame('Value could not be "1".', $errors[0]);
//     }
}