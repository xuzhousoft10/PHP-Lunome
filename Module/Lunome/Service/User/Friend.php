<?php
/**
 * 
 */
namespace X\Module\Lunome\Service\User;

/**
 * 
 */
use X\Module\Lunome\Model\AccountFriendshipModel;
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression;
use X\Module\Lunome\Model\AccountModel;
use X\Service\XDatabase\Core\SQL\Condition\Builder as SQLConditionBuilder;

/**
 * Handle all friend operations.
 */
class Friend {
    /**
     * @return array
     */
    public function getAll( $position=0, $length=0 ) {
        $accountTableName = AccountModel::model()->getTableFullName();
        $condition = array();
        $condition['account_him'] = new SQLExpression($accountTableName.'.id');
        $condition['account_me'] = $this->getCurrentUserId();
        $condition = AccountFriendshipModel::query()->activeColumns(array('account_him'))->find($condition);
        $condition = SQLConditionBuilder::build()->exists($condition);
        $accounts = AccountModel::model()->findAll($condition, $length, $position);
        foreach ( $accounts as $index => $account ) {
            $accounts[$index] = $account->toArray();
        }
        return $accounts;
    }
    
    private $userService = null;
    
    public function __construct($userService) {
        $this->userService = $userService;
    }
    
    private function getCurrentUserId() {
        $currentUser = $this->userService->getCurrentUser();
        return $currentUser['ID'];
    }
}