<?php
/**
 * The basic model class
 */
namespace X\Util\Model;

/**
 * Use statements
 */
use X\Core\X;
use X\Service\XDatabase\Core\ActiveRecord\XActiveRecord;
use X\Module\Account\Service\Account\Service as AccountService;

/**
 * The basic model class
 * @property string $record_created_at
 * @property string $record_created_by
 */
abstract class Basic extends XActiveRecord {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::init()
     */
    protected function init() {
        parent::init();
        if ( $this->has('id') ) {
            $this->getAttribute('id')->setValueBuilder(array($this, 'buildId'));
        }
        
        if ( $this->has('record_created_at') ) {
            $this->getAttribute('record_created_at')->setValueBuilder(array($this, 'buildRecordCreatedAt'));
        }
        
        if ( $this->has('record_created_by') ) {
            $this->getAttribute('record_created_by')->setValueBuilder(array($this, 'buildRecordCreatedBy'));
        }
    }
    
    /**
     * @param unknown $record
     */
    public function buildId( $record ) {
        return$this->generateUUID();
    }
    
    /**
     * @return string
     */
    public function buildRecordCreatedAt() {
        return date('Y-m-d H:i:s');
    }
    
    /**
     * @return string
     */
    public function buildRecordCreatedBy() {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $currentAccount = $accountService->getCurrentAccount();
        return (null===$currentAccount) ? null : $currentAccount->getID();
    }
    
    /**
     * @return string
     */
    protected function generateUUID () {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0x0fff ) | 0x4000,
                mt_rand( 0, 0x3fff ) | 0x8000,
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ));
    }
    
    /**
     * @return string
     */
    public static function getClassName() {
        return get_called_class();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Service\XDatabase\Core\ActiveRecord\XActiveRecord::getTableNamePrefix()
     */
    protected function getTableNamePrefix() {
        return 'lunome_';
    }
}