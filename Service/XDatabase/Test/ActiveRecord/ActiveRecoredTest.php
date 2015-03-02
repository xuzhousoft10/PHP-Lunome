<?php
namespace X\Service\XDatabase\Test\ActiveRecord;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Test\Fixture\ActiveRecord\TestActiveRecord4AR;
/**
 * 
 */
class ActiveRecoredTest extends ServiceTestCase {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Test\Util\ServiceTestCase::setUp()
     */
    protected function setUp() {
        parent::setUp();
        
        $ar = new TestActiveRecord4AR();
        $columns = array(
            'id'    => $ar->getAttribute('id')->toString(),
            'value' => $ar->getAttribute('value')->toString(),
            'status'=> $ar->getAttribute('status')->toString(),
            'mark'  => $ar->getAttribute('mark')->toString(),
        );
        Manager::create($ar->getTableFullName(), $columns);
        $ar = null;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\TestCase\ServiceTestCase::tearDown()
     */
    protected function tearDown() {
        $ar = new TestActiveRecord4AR();
        Manager::open($ar->getTableFullName())->drop();
        $ar = null;
        parent::tearDown();
    }
    
    /**
     * 
     */
    public function test_query() {
        $query = TestActiveRecord4AR::query()->toString();
        $this->assertSame("SELECT * FROM `test_activerecord_4_ar` LIMIT 1", $query);
    }
    
//     /**
//      * 
//      */
//     public function test_create() {
//         $record = TestActiveRecord::create(array('id'=>1));
//         $this->assertTrue($record->getIsNew());
//         $this->assertTrue($record->getAttribute('id')->getIsDirty());
        
//         $record = TestActiveRecord::create(array('id'=>1), false);
//         $this->assertFalse($record->getIsNew());
//         $this->assertFalse($record->getAttribute('id')->getIsDirty());
//     }
    
//     /**
//      * 
//      */
//     public function test_insert() {
//         $record = new TestActiveRecord();
//         $record->id = 1;
//         $record->mark = 1;
//         $record->status = 1;
//         $record->value = 1;
//         $this->assertTrue($record->getIsNew());
//         $record->save();
//         $this->assertTrue(TestActiveRecord::model()->exists(array('id'=>1)));
//         $this->assertFalse($record->getIsNew());
//     }
    
//     /**
//      * 
//      */
//     public function test_update() {
//         $record = new TestActiveRecord();
//         $record->id = 1;
//         $record->mark = 1;
//         $record->status = 1;
//         $record->value = 1;
//         $this->assertTrue($record->getIsNew());
//         $record->save();
//         $this->assertTrue(TestActiveRecord::model()->exists(array('id'=>1)));
//         $this->assertFalse($record->getIsNew());
        
//         $record->value=2;
//         $record->save();
//         $this->assertTrue(TestActiveRecord::model()->exists(array('value'=>2)));
        
//         $record->save();
//         $this->assertTrue(TestActiveRecord::model()->exists(array('value'=>2)));
//     }
}