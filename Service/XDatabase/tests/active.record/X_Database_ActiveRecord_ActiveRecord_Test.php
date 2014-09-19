<?php
/**
 * ActiveRecordColumnTest.php
 *
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 */
namespace X\Database\Test\ActiveRecord;

/**
 * Import namespaces
 */
use X\Database\Test\Resources\ActiveRecordTester;
use X\Database\Test\Resources\ActiveRecordNoPkTester;
use X\Database\Test\Resources\ActiveRecordEventTester;
use X\Database\ActiveRecord\ActiveRecord;
use X\Database\Table\Management;
use X\Database\SQL\Condition\Builder;
use X\Database\Test\Resources\ActiveRecordMember;
use X\Database\Test\Resources\ActiveRecordProfile;
use X\Database\Test\Resources\ActiveRecordChildren;
use X\Database\Test\Resources\ActiveRecordMemberActiveRecordMember;
use X\Database\Test\Resources\ActiveRecordGroup;

/**
 * Require needed files.
 */
require_once TEST_RESOURCE_PATH.'active.records/active.record.tester.php';
require_once TEST_RESOURCE_PATH.'active.records/active.record.no.pk.tester.php';
require_once TEST_RESOURCE_PATH.'active.records/active.record.event.tester.php';
require_once TEST_RESOURCE_PATH.'active.records/active.record.member.php';
require_once TEST_RESOURCE_PATH.'active.records/active.record.profile.php';
require_once TEST_RESOURCE_PATH.'active.records/active.record.children.php';
require_once TEST_RESOURCE_PATH.'active.records/active.record.group.php';
require_once TEST_RESOURCE_PATH.'active.records/active.record.member.active.record.member.php';

/**
 * ActiveRecord test case.
 */
class X_Database_ActiveRecord_ActiveRecord_Test extends \PHPUnit_Framework_TestCase {
    /**
     * __destruct
     */
    public function __destruct() {
        
    }
    
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
     * testRegisterEventHandler
     */
    public function testRegisterEventHandler() {
        $tester = new ActiveRecordEventTester();
        
        # You can not register a handler for a not supported event.
        try { 
            $tester->registerEventHandlerTester('not-support-event', null);
            $this->assertTrue(false);
        } catch ( \X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        
        # You can not register handler if handler is not callable.
        try {
            $tester->registerEventHandlerTester(ActiveRecord::ON_AFTER_DELETE, null);
            $this->assertTrue(false);
        } catch ( \X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        try {
            $tester->registerEventHandlerTester(ActiveRecord::ON_AFTER_DELETE, 'non-exists-function');
            $this->assertTrue(false);
        } catch ( \X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        
        $tester->registerEventHandlerTester(ActiveRecord::ON_AFTER_DELETE, 
                '\X\Database\Test\Resources\event_handler_for_test_register_event');
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_AFTER_DELETE, 
                '\X\Database\Test\Resources\event_handler_for_test_register_event'));
        
        $tester->registerEventHandlerTester(ActiveRecord::ON_AFTER_DELETE, 
                'testEventHandlerProtected');
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_AFTER_DELETE, 
                array($tester, 'testEventHandlerProtected')));
        
        $tester->registerEventHandlerTester(ActiveRecord::ON_AFTER_DELETE, 
                array($tester, 'testEventHandlerWithObject'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_AFTER_DELETE, 
                array($tester, 'testEventHandlerWithObject')));
    }
    
    /**
     * testOnAfterSave
     */
    public function testOnAfterSave() {
        $tester = new ActiveRecordEventTester();
        
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester', 
                $tester->prettyNameEventRegisterTester('onAfterSave', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_AFTER_SAVE, 
                array($tester, 'testEventHandlerProtected')));
    }
    
    /**
     * testOnAfterSave
     */
    public function testOnAfterInsert() {
        $tester = new ActiveRecordEventTester();
    
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester',
                $tester->prettyNameEventRegisterTester('onAfterInsert', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_AFTER_INSERT,
                array($tester, 'testEventHandlerProtected')));
    }
    
    /**
     * testOnAfterSave
     */
    public function testOnAfterUpdate() {
        $tester = new ActiveRecordEventTester();
    
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester',
                $tester->prettyNameEventRegisterTester('onAfterUpdate', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_AFTER_UPDATE,
                array($tester, 'testEventHandlerProtected')));
    }
    
    /**
     * testOnAfterSave
     */
    public function testOnAfterValidate() {
        $tester = new ActiveRecordEventTester();
    
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester',
                $tester->prettyNameEventRegisterTester('onAfterValidate', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_AFTER_VALIDATE,
                array($tester, 'testEventHandlerProtected')));
    }
    
    /**
     * testOnAfterSave
     */
    public function testOnAfterFind() {
        $tester = new ActiveRecordEventTester();
    
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester',
                $tester->prettyNameEventRegisterTester('onAfterFind', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_AFTER_FIND,
                array($tester, 'testEventHandlerProtected')));
    }
    
    /**
     * testOnAfterSave
     */
    public function testOnAfterDelete() {
        $tester = new ActiveRecordEventTester();
    
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester',
                $tester->prettyNameEventRegisterTester('onAfterDelete', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_AFTER_DELETE,
                array($tester, 'testEventHandlerProtected')));
    }
    
    /**
     * testOnAfterSave
     */
    public function testOnBeforeSave() {
        $tester = new ActiveRecordEventTester();
    
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester',
                $tester->prettyNameEventRegisterTester('onBeforeSave', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_BEFORE_SAVE,
                array($tester, 'testEventHandlerProtected')));
    }
    /**
     * testOnAfterSave
     */
    public function testOnBeforeInsert() {
        $tester = new ActiveRecordEventTester();
    
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester',
                $tester->prettyNameEventRegisterTester('onBeforeInsert', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_BEFORE_INSERT,
                array($tester, 'testEventHandlerProtected')));
    }
    /**
     * testOnAfterSave
     */
    public function testOnBeforeUpdate() {
        $tester = new ActiveRecordEventTester();
    
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester',
                $tester->prettyNameEventRegisterTester('onBeforeUpdate', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_BEFORE_UPDATE,
                array($tester, 'testEventHandlerProtected')));
    }
    /**
     * testOnAfterSave
     */
    public function testOnBeforeValidate() {
        $tester = new ActiveRecordEventTester();
    
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester',
                $tester->prettyNameEventRegisterTester('onBeforeValidate', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_BEFORE_VALIDATE,
                array($tester, 'testEventHandlerProtected')));
    }
    /**
     * testOnAfterSave
     */
    public function testOnBeforeFind() {
        $tester = new ActiveRecordEventTester();
    
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester',
                $tester->prettyNameEventRegisterTester('onBeforeFind', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_BEFORE_FIND,
                array($tester, 'testEventHandlerProtected')));
    }
    /**
     * testOnAfterSave
     */
    public function testOnBeforeDelete() {
        $tester = new ActiveRecordEventTester();
    
        $this->assertInstanceOf('\X\Database\Test\Resources\ActiveRecordEventTester',
                $tester->prettyNameEventRegisterTester('onBeforeDelete', 'testEventHandlerProtected'));
        $this->assertTrue($tester->hasHandlerOnEvent(ActiveRecord::ON_BEFORE_DELETE,
                array($tester, 'testEventHandlerProtected')));
    }
    
    /**
     * testTrigerSelfEvent
     */
    public function testTrigerSelfEvent() {
        $tester = new ActiveRecordEventTester();
        
        $tester->prettyNameEventRegisterTester('onBeforeDelete', 'eventHandler1');
        $tester->prettyNameEventRegisterTester('onBeforeDelete', 'eventHandler2');
        $tester->trigerSelfEventTester('non-exists-event');
        $this->assertEquals(array(), $tester->executedEvents);
        $tester->trigerSelfEventTester(ActiveRecord::ON_BEFORE_DELETE);
        $this->assertTrue(false !== array_search('eventHandler1', $tester->executedEvents));
        $this->assertTrue(false !== array_search('eventHandler2', $tester->executedEvents));
    }
    
    /**
     * testAfterSave
     */
    public function testAfterSave() {
        $tester = new ActiveRecordEventTester();
        $this->assertTrue($tester->getIsNew());
        $tester->prettyNameEventRegisterTester('onAfterSave', 'sigleEventHandler');
        $tester->prettyNameTriggerTester('afterSave');
        $this->assertFalse($tester->getIsNew());
        $this->assertTrue($tester->sigleEventExecuted);
    }
    
    /**
     * 
     */
    public function testAfterInser() {
        $tester = new ActiveRecordTester();
        $tester->col_int = 4;
        $this->assertFalse($tester->afterInsertEventExecuted);
        $tester->save();
        $this->assertTrue($tester->afterInsertEventExecuted);
        $tester->delete();
    }
    
    /**
     * 
     */
    public function testAfterUpdate() {
        $tester = new ActiveRecordEventTester();
        $tester->prettyNameEventRegisterTester('onAfterUpdate', 'sigleEventHandler');
        $tester->prettyNameTriggerTester('afterUpdate');
        $this->assertTrue($tester->sigleEventExecuted);
    }
    
    /**
     * 
     */
    public function testAfterValidate() {
        $tester = new ActiveRecordEventTester();
        $tester->prettyNameEventRegisterTester('onAfterValidate', 'sigleEventHandler');
        $tester->prettyNameTriggerTester('afterValidate');
        $this->assertTrue($tester->sigleEventExecuted);
    }
    
    /**
     * 
     */
    public function testAfterFind() {
        $tester = new ActiveRecordEventTester();
        $tester->prettyNameEventRegisterTester('onAfterFind', 'sigleEventHandler');
        $tester->prettyNameTriggerTester('afterFind');
        $this->assertTrue($tester->sigleEventExecuted);
    }
    
    /**
     * 
     */
    public function testAfterDelete() {
        $tester = new ActiveRecordEventTester();
        $tester->prettyNameEventRegisterTester('onAfterDelete', 'sigleEventHandler');
        $tester->prettyNameTriggerTester('afterDelete');
        $this->assertTrue($tester->sigleEventExecuted);
    }
    
    /**
     * 
     */
    public function testBeforeSave() {
        $tester = new ActiveRecordEventTester();
        $tester->prettyNameEventRegisterTester('onBeforeSave', 'sigleEventHandler');
        $tester->prettyNameTriggerTester('beforeSave');
        $this->assertTrue($tester->sigleEventExecuted);
    }
    
    /**
     * 
     */
    public function testBeforeInsert() {
        $tester = new ActiveRecordEventTester();
        $tester->prettyNameEventRegisterTester('onBeforeInsert', 'sigleEventHandler');
        $tester->prettyNameTriggerTester('beforeInsert');
        $this->assertTrue($tester->sigleEventExecuted);
    }
    
    /**
     * 
     */
    public function testBeforeUpdate() {
        $tester = new ActiveRecordEventTester();
        $tester->prettyNameEventRegisterTester('onBeforeUpdate', 'sigleEventHandler');
        $tester->prettyNameTriggerTester('beforeUpdate');
        $this->assertTrue($tester->sigleEventExecuted);
    }
    
    /**
     * 
     */
    public function testBeforeValidate() {
        $tester = new ActiveRecordEventTester();
        $tester->prettyNameEventRegisterTester('onBeforeValidate', 'sigleEventHandler');
        $tester->prettyNameTriggerTester('beforeValidate');
        $this->assertTrue($tester->sigleEventExecuted);
    }
    
    /**
     * 
     */
    public function testBeforeFind() {
        $tester = new ActiveRecordEventTester();
        $tester->prettyNameEventRegisterTester('onBeforeFind', 'sigleEventHandler');
        $tester->prettyNameTriggerTester('beforeFind');
        $this->assertTrue($tester->sigleEventExecuted);
    }
    
    /**
     * 
     */
    public function testBeforeDelete() {
        $tester = new ActiveRecordEventTester();
        $tester->prettyNameEventRegisterTester('onBeforeDelete', 'sigleEventHandler');
        $tester->prettyNameTriggerTester('beforeDelete');
        $this->assertTrue($tester->sigleEventExecuted);
    }
    
    /**
     * 
     */
    public function testAddScope() {
        $tester = new ActiveRecordTester();
        $condition = array('col_1'=>'val1');
        $this->assertEquals($condition, $tester->addScopeTestHelper('test-scope-1', $condition));
    }
    
    /**
     * 
     */
    public function testAddDefaultScope() {
        $tester = new ActiveRecordTester();
        $condition = array('col_1'=>'val1');
        $actual = $tester->addDefaultScopeTestHelper($condition);
        $this->assertEquals($condition, $actual);
    }
    
    /**
     * 
     */
    public function testScope() {
        $tester = new ActiveRecordTester();
        $tester->scopeTestHelper();
        $this->assertEquals(array('col_int'=>3), $tester->getScope(ActiveRecord::SCOPE_DEFAULT_NAME));
        $this->assertEquals(array('col_int'=>2), $tester->getScope('test-helper-scope-2'));
        $this->assertEquals(array('col_int'=>1), $tester->getScope('test-helper-scope-1'));
    }
    
    /**
     *
     */
    public function testWithScope() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $tester = new ActiveRecordTester();
        $results = $tester->withoutScope()->findAll();
        $this->assertEquals(3, count($results));
        
        $results = $tester->withScope(ActiveRecordTester::SCOPE_COL_INT_IS_1)->findAll();
        $this->assertEquals(1, count($results));
    }
    
    /**
     *
     */
    public function testWithoutScope() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $tester = new ActiveRecordTester();
        $results = $tester->withScope(ActiveRecordTester::SCOPE_COL_INT_IS_1)->findAll();
        $this->assertEquals(1, count($results));
            
        $results = $tester->withoutScope()->findAll();
        $this->assertEquals(3, count($results));
    }

    /**
     * 
     */
    public function testMergeConditionWithScope() {
        $tester = new ActiveRecordTester();
        $this->assertNull($tester->withoutScope()->mergeConditionWithScopeHelper(null));
        $this->assertNull($tester->withScope('none-exists-scope')->mergeConditionWithScopeHelper(null));
        
        $condition = Builder::build()->equals('col1', 'val1');
        $condition = $tester->withScope(ActiveRecordTester::SCOPE_COL_INT_IS_1)
            ->mergeConditionWithScopeHelper($condition);
        $this->assertEquals("`col1` = 'val1' AND ( `col_int` = '1' )", $condition->toString());
        
        $condition = $tester->withScope(ActiveRecordTester::SCOPE_COL_INT_IS_1)
            ->mergeConditionWithScopeHelper(null);
        $this->assertEquals(array('col_int'=>1), $condition);
    }
    
    /**
     * 
     */
    public function testDoFind() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        
        $tester = new ActiveRecordTester();
        $results = $tester->doFindTesterHelper();
        $this->assertEquals(array(), $results);
        
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $results = $tester->doFindTesterHelper(array('col_int'=>1));
        $this->assertEquals(1, count($results));
        $this->assertEquals(1, $results[0]->col_int);
        
        $results = $tester->withScope('col_int_is_1')->doFindTesterHelper();
        $this->assertEquals(1, count($results));
        $this->assertEquals(1, $results[0]->col_int);
        
        $results = $tester->withScope('col_int_is_1')->doFindTesterHelper(array('col_ai'=>1));
        $this->assertEquals(1, count($results));
        $this->assertEquals(1, $results[0]->col_int);
        
        $results = $tester->withScope('col_int_is_1')->doFindTesterHelper(array('col_ai'=>2));
        $this->assertEquals(0, count($results));
        
        $results = $tester->findAll();
        $this->assertTrue($tester->beforeFindEventHandler);
        foreach ( $results as $result ) {
            $this->assertTrue($result->afterFindEventHandler);
        }
    }
    
    /**
     * Tests ActiveRecord->find()
     */
    public function testFind() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $actual = ActiveRecordTester::model()->find('col_ai=1');
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordTester', $actual);
        
        $actual = ActiveRecordTester::model()->find(array('col_ai'=>1));
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordTester', $actual);
        
        $actual = ActiveRecordTester::model()->find(Builder::build(array('col_ai'=>1)));
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordTester', $actual);
        
        $actual = ActiveRecordTester::model()->find("col_ai='non-exists'");
        $this->assertNull($actual);
        
        $actual = ActiveRecordTester::model()->find(array('col_ai'=>'non-exists'));
        $this->assertNull($actual);
        
        $actual = ActiveRecordTester::model()->find(Builder::build(array('col_ai'=>'non-exists')));
        $this->assertNull($actual);
    }
    
    /**
     * 
     */
    public function testFindBySql() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'));
        
        $actual = ActiveRecordTester::model()->findBySql('col_ai=1');
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordTester', $actual);
        
        $actual = ActiveRecordTester::model()->findBySql("col_ai='non-exists'");
        $this->assertNull($actual);
    }
    
    /**
     * 
     */
    public function testFindByAttribute() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'));
        
        $actual = ActiveRecordTester::model()->findByAttribute(array('col_ai'=>1));
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordTester', $actual);
        
        $actual = ActiveRecordTester::model()->findByAttribute(array('col_ai'=>'non-exists'));
        $this->assertNull($actual);
    }
    
    /**
     * 
     */
    public function testFindByCondition() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $actual = ActiveRecordTester::model()->findByCondition(Builder::build(array('col_ai'=>1)));
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordTester', $actual);
        
        $actual = ActiveRecordTester::model()->findByCondition(Builder::build(array('col_ai'=>'non-exists')));
        $this->assertNull($actual);
    }
    
    /**
     * 
     */
    public function testFindByPrimaryKey() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $actual = ActiveRecordTester::model()->findByPrimaryKey('1');
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordTester', $actual);
        
        $actual = ActiveRecordTester::model()->findByPrimaryKey('non-exists');
        $this->assertNull($actual);
        
        try {
            ActiveRecordNoPkTester::model()->findByPrimaryKey('xyz');
            $this->assertTrue(false);
        } catch ( \X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
    }
    
    /**
     * 
     */
    public function testFindAllBySql() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $actual = ActiveRecordTester::model()->findAllBySql('col_ai>=1');
        $this->assertEquals(3, count($actual));
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordTester', $actual[0]);
        
        $actual = ActiveRecordTester::model()->findAllBySql("col_ai='non-exists'");
        $this->assertEquals(0, count($actual));
    }
    
    /**
     * 
     */
    public function testFindAllByAttributes() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '1'))
            ->insert(array('', '1'));
    
        $actual = ActiveRecordTester::model()->findAllByAttributes(array('col_int'=>1));
        $this->assertEquals(3, count($actual));
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordTester', $actual[0]);
    
        $actual = ActiveRecordTester::model()->findAllByAttributes(array('col_int'=>2));
        $this->assertEquals(0, count($actual));
    }
    
    /**
     *
     */
    public function testFindAllByCondition() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '1'))
            ->insert(array('', '1'));
    
        $actual = ActiveRecordTester::model()->findAllByCondition(Builder::build(array('col_int'=>1)), 2);
        $this->assertEquals(2, count($actual));
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordTester', $actual[0]);
    
        $actual = ActiveRecordTester::model()->findAllByCondition(Builder::build(array('col_int'=>2)));
        $this->assertEquals(0, count($actual));
    }
    
    /**
     * 
     */
    public function testDelete() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'));

        $this->assertTrue(ActiveRecordTester::model()->exists(array('col_int'=>1)));
        
        $tester = ActiveRecordTester::model()->findByAttribute(array('col_int'=>1));
        $this->assertTrue($tester->delete());
       
        $this->assertFalse(ActiveRecordTester::model()->exists(array('col_int'=>1)));
    }
    
    /**
     * 
     */
    public function testDoDeleteAll() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $this->assertEquals(3, ActiveRecordTester::model()->doDeleteAllTester(null, 0));
        
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        $actual = ActiveRecordTester::model()->withScope(ActiveRecordTester::SCOPE_COL_INT_IS_1)
            ->doDeleteAllTester(null, 0);
        $this->assertEquals(1, $actual);
    }
    
    /**
     * 
     */
    public function testDeleteAllBySql() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $this->assertEquals(3, ActiveRecordTester::model()->deleteAllBySql('col_int != 0'));
    }
    
    /**
     * 
     */
    public function testDeleteAllByAttributes() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $this->assertEquals(1, ActiveRecordTester::model()->deleteAllByAttributes(array('col_int'=>1)));
    }
    
    /**
     * 
     */
    public function testDeleteAllByCondition() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $condition = Builder::build()->notEquals('col_int', 0);
        $this->assertEquals(2, ActiveRecordTester::model()->deleteAllByCondition($condition, 2));
    }
    
    /**
     * 
     */
    public function testDoUpdateAll() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
            
        $values = array('col_int'=>'100');
        $actual = ActiveRecordTester::model()->doUpdateAllTester($values, 'col_ai != 0');
        $this->assertEquals(3, $actual);
        
        $results = ActiveRecordTester::model()->findAll();
        foreach ( $results as $result ) {
            $this->assertEquals(100, $result->col_int);
        }
    }
    
    /**
     * 
     */
    public function testUpdateAllBySql() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $values = array('col_int'=>'100');
        $actual = ActiveRecordTester::model()->updateAllBySql($values, 'col_ai != 0', 1);
        $this->assertEquals(1, $actual);
        
        $results = ActiveRecordTester::model()->findAllByAttributes(array('col_int'=>100));
        $this->assertEquals(1, count($results));
    }
    
    /**
     * 
     */
    public function testUpdateAllByAttributes() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $values = array('col_int'=>'100');
        $condition = Builder::build()->notEquals('col_int', 0);
        $actual = ActiveRecordTester::model()->updateAllByCondition($values, $condition);
        $this->assertEquals(3, $actual);
        
        $results = ActiveRecordTester::model()->findAllByAttributes(array('col_int'=>100));
        $this->assertEquals(3, count($results));
    }
    
    /**
     * 
     */
    public function testDoUpdateAllByCondition() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)
            ->insert(array('', '1'))
            ->insert(array('', '2'))
            ->insert(array('', '3'));
        
        $values = array('col_int'=>'100');
        $actual = ActiveRecordTester::model()->updateAllByAttributes($values, array('col_int'=>1));
        $this->assertEquals(1, $actual);
        
        $results = ActiveRecordTester::model()->findAllByAttributes(array('col_int'=>100));
        $this->assertEquals(1, count($results));
    }
    
    /**
     * 
     */
    public function testDoSaveUpdate() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        Management::open(ActiveRecordTester::TABLE_NAME)->insert(array('', '1'));
        $record = ActiveRecordTester::model()->findByAttribute(array('col_int'=>1));
        $record->col_int = 2;
        $this->assertTrue($record->doSaveUpdateTester());
        
        $record->tableName = 'non-exists-table';
        $this->assertFalse($record->doSaveUpdateTester());
    }
    
    /**
     * 
     */
    public function testDoSaveInsert() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        $record = new ActiveRecordTester();
        $record->col_int = 1;
        $this->assertTrue($record->doSaveInsertTester());
        
        $record->tableName = 'non-exists-table';
        $this->assertFalse($record->doSaveInsertTester());
    }
    
    /**
     * 
     */
    public function testSave() {
        Management::open(ActiveRecordTester::TABLE_NAME)->truncate();
        $record = new ActiveRecordTester();
        $record->col_int = 1;
        $this->assertTrue($record->save());
        $this->assertFalse($record->getIsNew());
        
        $record->col_int = 2;
        $this->assertTrue($record->save());
        
        $this->assertTrue(ActiveRecordTester::model()->exists(array('col_int'=>2)));
        
        $record->col_int = 'string';
        $this->assertFalse($record->save());
    }
    
    /**
     * 
     */
    public function testGetAttribute() {
        $record = new ActiveRecordTester();
        try {
            $record->getAttribute('non-exists-attribute');
            $this->assertTrue(false);
        } catch ( \X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        
        $record->col_int = 1;
        $this->assertEquals(1, $record->getAttribute('col_int'));
    }
    
    /**
     * 
     */
    public function testSetAttribute() {
        $record = new ActiveRecordTester();
        try {
            $record->setAttribute('non-exists-attribute', 1);
            $this->assertTrue(false);
        } catch ( \X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        
        try {
            $record->setAttribute('col_ai', 1);
            $this->assertTrue(false);
        } catch ( \X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        
        $record->col_int = 1;
        $this->assertEquals(1, $record->getAttribute('col_int'));
    }
    
    /**
     * 
     */
    public function testSetAttributes() {
        $record = new ActiveRecordTester();
        try {
            $record->setAttributes(array('non-exists-col'=>1));
            $this->assertTrue(false);
        } catch ( \X\Database\Exception $e ) {
            $this->assertTrue(true);
        }
        
        $record->setAttributes(array('col_int'=>1));
        $this->assertEquals(1, $record->getAttribute('col_int'));
    }
    
    /**
     * 
     */
    public function testHasAttribute() {
        $record = new ActiveRecordTester();
        $this->assertFalse($record->hasAttribute('non-exists-attr'));
        $this->assertTrue($record->hasAttribute('col_int'));
    }
    
    /**
     * 
     */
    public function testGetAttributeNames() {
        $record = new ActiveRecordTester();
        $attributes = array('col_ai', 'col_int');
        $this->assertEquals($attributes, $record->getAttributeNames());
    }
    
    /**
     * 
     */
    public function testToArray() {
        $record = new ActiveRecordTester();
        $record->col_ai = 1;
        $record->col_int = 2;
        $attributes = array('col_ai'=>1, 'col_int'=>2);
        $this->assertEquals($attributes, $record->toArray());
    }
    
    /**
     * 
     */
    public function testValidate() {
        $record = new ActiveRecordTester();
        $this->assertTrue($record->validate());
        
        $record->col_int = '100';
        $this->assertTrue($record->validate());
        
        $record->col_int = 'string';
        $this->assertFalse($record->validate());
    }
    
    /**
     * 
     */
    public function testValidateAttribute() {
        $record = new ActiveRecordTester();
        $this->assertTrue($record->validateAttribute('col_int'));
    
        $record->col_int = '100';
        $this->assertTrue($record->validateAttribute('col_int'));
    
        $record->col_int = 'string';
        $this->assertFalse($record->validateAttribute('col_int'));
    }
    
    /**
     * 
     */
    public function testHasError() {
        $record = new ActiveRecordTester();
        $this->assertTrue($record->validate());
        $this->assertFalse($record->hasError());
        
        $record->col_int = '100';
        $this->assertTrue($record->validate());
        $this->assertFalse($record->hasError());
        
        $record->col_int = 'string';
        $this->assertFalse($record->validate());
        $this->assertTrue($record->hasError());
    }
    
    /**
     *
     */
    public function testHasErrorOnAttribute() {
        $record = new ActiveRecordTester();
        $this->assertTrue($record->validate());
        $this->assertFalse($record->hasErrorOnAttribute('col_int'));
    
        $record->col_int = '100';
        $this->assertTrue($record->validate());
        $this->assertFalse($record->hasErrorOnAttribute('col_int'));
    
        $record->col_int = 'string';
        $this->assertFalse($record->validate());
        $this->assertTrue($record->hasErrorOnAttribute('col_int'));
    }
    
    /**
     * 
     */
    public function testGetErrors() {
        $record = new ActiveRecordTester();
        $this->assertTrue($record->validate());
        
        $record->col_int = 'string';
        $this->assertFalse($record->validate());
        $this->assertArrayHasKey('col_int', $record->getErrors());
    }
    
    /**
     *
     */
    public function testGetErrorOnAttribute() {
        $record = new ActiveRecordTester();
        $this->assertTrue($record->validate());
    
        $record->col_int = 'string';
        $this->assertFalse($record->validate());
        $this->assertGreaterThan(0, count($record->getErrorOnAttribute('col_int')));
    }
    
    /**
     * 
     */
    public function testHasOne() {
        Management::open(ActiveRecordMember::TABLE_NAME)->truncate();
        ActiveRecordMember::create()->save();
        ActiveRecordMember::create()->save();
        
        Management::open(ActiveRecordProfile::TABLE_NAME)->truncate();
        Management::open(ActiveRecordProfile::TABLE_NAME)->insert(array('', '1', '10'));
        
        $member = ActiveRecordMember::model()->findByPrimaryKey(1);
        $profile = $member->getActiveRecordProfile();
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordProfile', $profile);
        $this->assertEquals(10, $profile->age);
    }
    
    /**
     *
     */
    public function testHasMany() {
        Management::open(ActiveRecordMember::TABLE_NAME)->truncate();
        ActiveRecordMember::create()->save();
    
        Management::open(ActiveRecordChildren::TABLE_NAME)->truncate();
        Management::open(ActiveRecordChildren::TABLE_NAME)->insert(array('', '1', 'name1'));
        Management::open(ActiveRecordChildren::TABLE_NAME)->insert(array('', '1', 'name2'));
    
        $member = ActiveRecordMember::model()->findByPrimaryKey(1);
        $children = $member->getAllActiveRecordChildren();
        $this->assertEquals(2, count($children));
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordChildren', $children[0]);
        $this->assertEquals('name1', $children[0]->name);
    }
    
    /**
     * 
     */
    public function testManyToMany() {
        Management::open(ActiveRecordMember::TABLE_NAME)->truncate();
        ActiveRecordMember::create()->save();
        ActiveRecordMember::create()->save();
        ActiveRecordMember::create()->save();
        
        Management::open(ActiveRecordMemberActiveRecordMember::TABLE_NAME)->truncate();
        Management::open(ActiveRecordMemberActiveRecordMember::TABLE_NAME)->insert(array(1,2));
        Management::open(ActiveRecordMemberActiveRecordMember::TABLE_NAME)->insert(array(1,3));
        
        $member = ActiveRecordMember::model()->findByPrimaryKey(1);
        $friends = $member->getAllActiveRecordMember();
        $this->assertEquals(2, count($friends));
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordMember', $friends[0]);
    }
    
    /**
     * 
     */
    public function testBelongsTo() {
        Management::open(ActiveRecordMember::TABLE_NAME)->truncate();
        ActiveRecordMember::create(array('activerecordgroup_id'=>1))->save();
        ActiveRecordMember::create()->save();
        
        Management::open(ActiveRecordGroup::TABLE_NAME)->truncate();
        Management::open(ActiveRecordGroup::TABLE_NAME)->insert(array(''));
        
        $member = ActiveRecordMember::model()->findByPrimaryKey(1);
        $group = $member->getActiveRecordGroup();
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordGroup', $group);
        $this->assertEquals(1, $group->id);
        
        $group = $member->activeRecordGroup;
        $this->assertInstanceOf('X\Database\Test\Resources\ActiveRecordGroup', $group);
        $this->assertEquals(1, $group->id);
    }
}