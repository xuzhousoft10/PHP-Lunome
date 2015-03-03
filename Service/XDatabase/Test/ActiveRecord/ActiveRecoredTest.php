<?php
namespace X\Service\XDatabase\Test\ActiveRecord;
/**
 * 
 */
use X\Service\XDatabase\Test\Util\ServiceTestCase;
use X\Service\XDatabase\Core\Table\Manager;
use X\Service\XDatabase\Test\Fixture\ActiveRecord\TestActiveRecord4AR;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Service\XDatabase\Test\Fixture\ActiveRecord\TestActiveRecord4ARNoPrimaryKey;
use X\Service\XDatabase\Core\Util\Exception;
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
        Manager::create($ar->getTableFullName(), $columns, 'id');
        $ar = null;
        
        $ar = new TestActiveRecord4ARNoPrimaryKey();
        $columns = array(
            'value' => $ar->getAttribute('value')->toString(),
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
        
        $ar = new TestActiveRecord4ARNoPrimaryKey();
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
    
    /**
     * 
     */
    public function test_create() {
        $record = TestActiveRecord4AR::create(array('id'=>1));
        $this->assertTrue($record->getIsNew());
        $this->assertTrue($record->getAttribute('id')->getIsDirty());
        
        $record = TestActiveRecord4AR::create(array('id'=>1), false);
        $this->assertFalse($record->getIsNew());
        $this->assertFalse($record->getAttribute('id')->getIsDirty());
    }
    
    /**
     * 
     */
    public function test_save() {
        $record = new TestActiveRecord4AR();
        $record->id = 1;
        $record->mark = 1;
        $record->status = 1;
        $record->value = 1;
        $this->assertTrue($record->getIsNew());
        $record->save();
        $this->assertTrue(TestActiveRecord4AR::model()->exists(array('id'=>1)));
        $this->assertFalse($record->getIsNew());
        
        $record = new TestActiveRecord4AR();
        $record->id = 2;
        $record->mark = 2;
        $record->status = 1;
        $record->value = 1;
        $this->assertTrue($record->getIsNew());
        $record->save();
        $this->assertTrue(TestActiveRecord4AR::model()->exists(array('id'=>1)));
        $this->assertFalse($record->getIsNew());
        
        $record->value=2;
        $record->save();
        $this->assertTrue(TestActiveRecord4AR::model()->exists(array('value'=>2)));
        
        $record->save();
        $this->assertTrue(TestActiveRecord4AR::model()->exists(array('value'=>2)));
        
        try {
            $record->mark = null;
            $record->save();
            $this->fail('An exception should be throwed if validate failed before save.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_error() {
        $record = new TestActiveRecord4AR();
        $record->validate();
        $this->assertTrue($record->hasError('mark'));
        $errors = $record->getErrors();
        $this->assertSame(1, count($errors));
        $this->assertSame($errors['mark'], $record->getErrors('mark'));
        
        $record = new TestActiveRecord4AR();
        $record->addError('id', 'this is a test error.');
        $this->assertTrue($record->has('id'));
    }
    
    /**
     * 
     */
    public function test_find() {
        TestActiveRecord4AR::create(array('mark'=>1))->save();
        TestActiveRecord4AR::create(array('mark'=>2))->save();
        
        $result = TestActiveRecord4AR::model()->find(array('mark'=>1));
        $this->assertSame(1, (int)$result->get('mark'));
        
        $criteria = new Criteria();
        $criteria->addOrder('mark', 'DESC');
        $results = TestActiveRecord4AR::model()->findAll($criteria);
        $this->assertSame(2, count($results));
        $this->assertSame(2, (int)($results[0]->get('mark')));
        
        $result = TestActiveRecord4AR::model()->findByPrimaryKey(1);
        $this->assertSame(1, (int)$result->get('mark'));
        
        try {
            TestActiveRecord4ARNoPrimaryKey::model()->findByPrimaryKey('1');
            $this->fail('An exception should be throwed if there is no primary key and still try to find by primary key.');
        } catch ( Exception $e ){}
        
        try {
            TestActiveRecord4AR::model()->find('invalidate-condition');
            $this->fail('An exception should be throwed if there is any errors in sql.');
        } catch ( Exception $e ){}
    }
    
    /**
     * 
     */
    public function test_attributes() {
        $record = new TestActiveRecord4AR();
        $record->value = 1;
        $this->assertSame(1, $record->value);
        try {
            $value = $record->non_exists_attribute;
            $this->fail('An exception should be throwed if try to get non exists attribute.');
        } catch( Exception $e ){}
        
        try {
            $record->non_exists_attribute = 1;
            $this->fail('An exception should be throwed if try to set value to a non exists attribute.');
        } catch( Exception $e ){}
        
        $record->setAttributeValues(array('id'=>1, 'mark'=>2));
        $this->assertSame(1, $record->id);
        $this->assertSame(1, $record->value);
        $this->assertSame(2, $record->mark);
        
        $this->assertSame(array('id','value','mark','status'), $record->getAttributeNames());
        $this->assertSame('`'.$record->getTableFullName().'`.`value`', $record->getAttributeQueryName('value'));
        $this->assertSame(array(
            'id'=>1,
            'value'=>1,
            'mark'=>2,
            'status'=>$record->getAttribute('status')->getDefault(),
        ), $record->toArray());
    }
    
    /**
     * 
     */
    public function test_delete() {
        TestActiveRecord4AR::create(array('mark'=>1))->save();
        TestActiveRecord4AR::create(array('mark'=>2))->save();
        TestActiveRecord4AR::create(array('mark'=>3))->save();
        TestActiveRecord4AR::create(array('mark'=>4))->save();
        TestActiveRecord4AR::create(array('mark'=>5))->save();
        $this->assertSame(5, TestActiveRecord4AR::model()->count());
        
        TestActiveRecord4AR::model()->find(array('mark'=>1))->delete();
        $this->assertSame(4, TestActiveRecord4AR::model()->count());
        $this->assertFalse(TestActiveRecord4AR::model()->exists(array('mark'=>1)));
        
        TestActiveRecord4AR::model()->deleteAll();
        $this->assertSame(0, TestActiveRecord4AR::model()->count());
        
        TestActiveRecord4ARNoPrimaryKey::create(array('value'=>1))->save();
        $this->assertSame(1, TestActiveRecord4ARNoPrimaryKey::model()->count());
        TestActiveRecord4ARNoPrimaryKey::model()->find(array('value'=>1))->delete();
        $this->assertSame(0, TestActiveRecord4ARNoPrimaryKey::model()->count());
    }
    
    /**
     * 
     */
    public function test_updateAll() {
        TestActiveRecord4AR::create(array('mark'=>1))->save();
        TestActiveRecord4AR::create(array('mark'=>2))->save();
        TestActiveRecord4AR::create(array('mark'=>3))->save();
        TestActiveRecord4AR::create(array('mark'=>4))->save();
        TestActiveRecord4AR::create(array('mark'=>5))->save();
        
        TestActiveRecord4AR::model()->updateAll(array('value'=>'new-value'));
        $this->assertSame(5, TestActiveRecord4AR::model()->count(array('value'=>'new-value')));
        
        $criteria = new Criteria();
        $criteria->limit = 1;
        TestActiveRecord4AR::model()->updateAll(array('value'=>'new-value-2'), $criteria);
        $this->assertSame(4, TestActiveRecord4AR::model()->count(array('value'=>'new-value')));
        $this->assertSame(1, TestActiveRecord4AR::model()->count(array('value'=>'new-value-2')));
    }
    
    /**
     * 
     */
    public function test_max() {
        TestActiveRecord4AR::create(array('mark'=>1))->save();
        TestActiveRecord4AR::create(array('mark'=>2))->save();
        TestActiveRecord4AR::create(array('mark'=>3))->save();
        TestActiveRecord4AR::create(array('mark'=>4))->save();
        TestActiveRecord4AR::create(array('mark'=>5))->save();
        
        $this->assertSame('5', TestActiveRecord4AR::model()->getMax('mark'));
        $this->assertSame('3', TestActiveRecord4AR::model()->getMax('mark', array('mark'=>array('1','2','3'))));
    }
}