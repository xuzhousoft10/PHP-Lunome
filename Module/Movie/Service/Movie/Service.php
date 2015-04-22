<?php
namespace X\Module\Movie\Service\Movie;
/**
 * 
 */
use X\Core\X;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Module\Movie\Service\Movie\Core\Instance\Account;
use X\Module\Movie\Service\Movie\Core\Manager\RegionManager;
use X\Module\Movie\Service\Movie\Core\Manager\LanguageManager;
use X\Module\Movie\Service\Movie\Core\Manager\CategoryManager;
use X\Module\Movie\Service\Movie\Core\Model\MovieModel;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
use X\Service\XDatabase\Service as DatabaseService;
use X\Module\Movie\Service\Movie\Core\Model\MovieUserMarkModel;
use X\Service\XDatabase\Core\SQL\Builder as SQLBuilder;
use X\Service\XDatabase\Core\SQL\Func\Count;
use X\Service\XDatabase\Core\SQL\Util\Expression as SQLExpression;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Movie\Service\Movie\Core\Model\MovieUserRateModel;
use X\Service\XDatabase\Core\SQL\Condition\Condition;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
/**
 * 
 */
class Service extends \X\Core\Service\XService {
    /**
     * @var string
     */
    protected static $serviceName = 'Movie';
    
    /**
     * @param string $condition
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Movie[]
     */
    public function find($condition=null) {
        if ( !($condition instanceof Criteria) ) {
            $criteria = new Criteria();
            $criteria->condition = $condition;
            $condition = $criteria;
        } else {
            $criteria = $condition;
        }
        
        if ( !$criteria->hasOrder() ) {
            $criteria->addOrder('date', 'DESC');
        }
        
        $movies = MovieModel::model()->findAll($criteria);
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = new Movie($movie);
        }
        return $movies;
    }
    public function count() {}
    public function getDirectoryManager() {}
    public function getActorManager() {}
    
    public function getTopListByMark( $mark, $limit ) {
        /* @var $DBService DatabaseService */
        $DBService = X::system()->getServiceManager()->get(DatabaseService::getServiceName());
        
        $modelTableName = MovieModel::model()->getTableFullName();
        $markTableName = MovieUserMarkModel::model()->getTableFullName();
        
        $query = SQLBuilder::build()->select()
            ->expression('medias.id', 'id')
            ->expression(new Count('marks.id'), 'mark_count')
            ->from($modelTableName, 'medias')
            ->from($markTableName, 'marks')
            ->where(array('marks.mark'=>$mark, 'medias.id'=>new SQLExpression('`marks`.`movie_id`')))
            ->limit($limit)
            ->groupBy('marks.movie_id')
            ->orderBy('mark_count', 'DESC')
            ->toString();
        $result = $DBService->getDatabaseManager()->get()->query($query);
        
        $list = array();
        foreach ( $result as $item ) {
            $list[] = $item['id'];
        }
        if ( empty($list) ) {
            return array();
        }
        return $this->find(array('id'=>$list));
    }
    
    /**
     * @param string $id
     * @return boolean
     */
    public function has( $id ) {
        return MovieModel::model()->exists(array('id'=>$id));
    }
    
    /**
     * @param string $id
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Movie
     */
    public function get( $id ) {
        $movie = MovieModel::model()->findByPrimaryKey($id);
        if ( null === $movie ) {
            return null;
        }
        return new Movie($movie);
    }
    
    /**
     * @var CategoryManager
     */
    private $categoryManager = null;
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Manager\CategoryManager
     */
    public function getCategoryManager(){
        if ( null === $this->categoryManager ) {
            $this->categoryManager = new CategoryManager();
        }
        return $this->categoryManager;
    }
    
    /**
     * @var LanguageManager
     */
    private $languageModel = null;
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Manager\LanguageManager
     */
    public function getLanguageManager(){
        if ( null === $this->languageModel ) {
            $this->languageModel = new LanguageManager();
        }
        return $this->languageModel;
    }
    
    /**
     * @var RegionManager
     */
    private $regionManager = null;
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Manager\RegionManager
     */
    public function getRegionManger() {
        if ( null === $this->regionManager ) {
            $this->regionManager = new RegionManager();
        }
        return $this->regionManager;
    }
    
    /**
     * @var Account
     */
    private $currentMovieAccount = null;
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Account
     */
    public function getCurrentAccount() {
        if ( null === $this->currentMovieAccount ) {
            /* @var $accountService AccountService */
            $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
            $account = $accountService->getCurrentAccount();
            $this->currentMovieAccount = new Account($account);
        }
        return $this->currentMovieAccount;
    }
    
    /**
     * @param string $accountID
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Account
     */
    public function getAccount( $accountID ) {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        $account = $accountService->get($accountID);
        return new Account($account);
    }
    
    /**
     * @param array $accounts
     * @param mixed $condition
     * @param boolean $isLiked
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Movie
     */
    private function getWatchedMoviesByScoreAndAccounts( $accounts, $condition, $isLiked ) {
        $markAccountsCondition = SQLBuilder::build()->select()
            ->expression('id')
            ->from(MovieUserMarkModel::model()->getTableFullName(), 't2')
            ->where(ConditionBuilder::build()
                    ->is('t1.id', new SQLExpression('`t2`.`id`'))
                    ->is('mark', Movie::MARK_WATCHED)
                    ->in('t2.account_id', $accounts)
            );
        
        $markCondition = SQLBuilder::build()->select()
            ->expression('movie_id')
            ->from(MovieUserMarkModel::model()->getTableFullName(), 't1')
            ->groupBy('t1.movie_id')
            ->having('COUNT(t1.account_id) = '.count($accounts))
            ->where(ConditionBuilder::build()->exists($markAccountsCondition));
        
        $movieCondition = SQLBuilder::build()->select()
            ->expression('movie_id')
            ->from(MovieUserRateModel::model()->getTableFullName())
            ->groupBy('movie_id')
            ->where(ConditionBuilder::build()
                    ->in('account_id', $accounts)
                    ->addSigleCondition('score', $isLiked?Condition::OPERATOR_GREATER_OR_EQUAL : Condition::OPERATOR_LESS_THAN, 5)
                    ->in('movie_id', $markCondition)
            );
        
        $criteria = new Criteria();
        if ( $condition instanceof Criteria ) {
            $criteria = clone $condition;
        } else {
            $criteria->condition = $condition;
        }
        
        if ( !($criteria->condition instanceof ConditionBuilder) ) {
            $criteria->condition = ConditionBuilder::build($criteria->condition);
        }
        
        $criteria->condition->in('id', $movieCondition);
        $movies = MovieModel::model()->findAll($criteria);
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = new Movie($movie);
        }
        return $movies;
    }
    
    /**
     * @param array $accounts
     * @param mixed $condition
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Movie
     */
    public function getLikedByAccounts($accounts, $condition=null ) {
        return $this->getWatchedMoviesByScoreAndAccounts($accounts, $condition, true);
    }
    
    /**
     * @param array $accounts
     * @param mixed $condition
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Movie
     */
    public function getDislikedByAccounts($accounts, $condition=null ) {
        return $this->getWatchedMoviesByScoreAndAccounts($accounts, $condition, false);
    }
    
    /**
     * @param array $accounts
     * @param mixed $condition
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Movie
     */
    public function getInterestedByAccounts( $accounts, $condition=null ) {
        $marks = SQLBuilder::build()->select()
            ->expression('id')
            ->from(MovieUserMarkModel::model()->getTableFullName(), 'mark_accounts')
            ->where(ConditionBuilder::build()
                ->is('mark', Movie::MARK_INTERESTED)
                ->in('account_id', $accounts)
                ->addCondition(new SQLExpression('mark_movies.id=mark_accounts.id'))
            );
        
        $markTable = MovieUserMarkModel::model()->getTableFullName();
        $sql = SQLBuilder::build()->select()
            ->expression('movie_id')
            ->from($markTable, 'mark_movies')
            ->groupBy('movie_id')
            ->where(ConditionBuilder::build()->exists($marks))
            ->having('COUNT(`movie_id`)='.count($accounts));
        
        $criteria = new Criteria();
        if ( $condition instanceof Criteria ) {
            $criteria = clone $condition;
        } else {
            $criteria->condition = $condition;
        }
        
        if ( !($criteria->condition instanceof ConditionBuilder) ) {
            $criteria->condition = ConditionBuilder::build($criteria->condition);
        }
        
        $criteria->condition->in('id', $sql);
        $movies = MovieModel::model()->findAll($criteria);
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = new Movie($movie);
        }
        return $movies;
    }
}