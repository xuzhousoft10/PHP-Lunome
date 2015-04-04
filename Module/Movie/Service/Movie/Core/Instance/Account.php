<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Core\Model\MovieModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieUserMarkModel;
use X\Service\XDatabase\Core\SQL\Util\Expression as SQLExpression;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Movie\Service\Movie\Core\Model\MovieUserRateModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieDirectorModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieDirectorMapModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieActorModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieActorMapModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieCategoryMapModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieShortCommentModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieInvitationModel;
/**
 * 
 */
class Account {
    /**
     * @var  \X\Module\Account\Service\Account\Core\Instance\Account
     */
    private $account = null; 
    
    /**
     * @var string
     */
    private $accountID = null;
    
    /**
     * @param \X\Module\Account\Service\Account\Core\Instance\Account $account
     */
    public function __construct( $account ) {
        $this->account = $account;
        $this->accountID = $account->getID();
    }
    
    /**
     * @param mixed $condition
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Movie[]
     */
    public function findUnmarked( $condition ) {
        if ( !($condition instanceof Criteria) ) {
            $criteria = new Criteria();
            $criteria->condition = $condition;
            $condition = $criteria;
        }
        
        /* @var $condition Criteria */
        $basicCondition = $this->getUnmarkedCondition();
        $basicCondition->addCondition($this->getExtenCondition($condition->condition));
        $condition->condition = $basicCondition;
        if ( !$condition->hasOrder() ) {
            $condition->addOrder('date', 'DESC');
        }
        
        $movies = MovieModel::model()->findAll($condition);
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = new Movie($movie);
        }
        return $movies;
    }
    
    /**
     * @param mixed $condition
     * @return number
     */
    public function countUnmarked( $condition=array() ) {
        $basicCondition = $this->getUnmarkedCondition();
        $basicCondition->addCondition($this->getExtenCondition($condition));
        $count = MovieModel::model()->count($basicCondition);
        return $count;
    }
    
    /**
     * @param integer $mark
     * @param mixed $condition
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Movie[]
     */
    public function findMarked( $mark, $condition=array() ) {
        if ( !($condition instanceof Criteria) ) {
            $criteria = new Criteria();
            $criteria->condition = $condition;
            $condition = $criteria;
        }
        
        /* @var $condition Criteria */
        $basicCondition = $this->getMarkedCondition($mark);
        $basicCondition->addCondition($this->getExtenCondition($condition->condition));
        $condition->condition = $basicCondition;
        if ( !$condition->hasOrder() ) {
            $condition->addOrder('date', 'DESC');
        }
        
        $movies = MovieModel::model()->findAll($condition);
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = new Movie($movie);
        }
        return $movies;
    }
    
    /**
     * @param integer $mark
     * @param mixed $condition
     * @return number
     */
    public function countMarked($mark, $condition=array()) {
        $basicCondition = $this->getMarkedCondition($mark);
        $basicCondition->addCondition($this->getExtenCondition($condition));
        $count = MovieModel::model()->count($basicCondition);
        return $count;
    }
    
    /**
     * @return \X\Service\XDatabase\Core\SQL\Condition\Builder
     */
    private function getUnmarkedCondition() {
        $tableName = MovieModel::model()->getTableFullName();
        
        /* Generate basic condition to ignore marked movies. */
        $markedCondition = array();
        $markedCondition['movie_id'] = new SQLExpression($tableName.'.id');
        $markedCondition['account_id'] = $this->accountID;
        $markedMedias = MovieUserMarkModel::query()->addExpression('movie_id')->find($markedCondition);
        $basicCondition = ConditionBuilder::build()->notExists($markedMedias);
        return $basicCondition;
    }
    
    /**
     * @param integer $mark
     * @return \X\Service\XDatabase\Core\SQL\Condition\Builder
     **/
    private function getMarkedCondition( $mark ) {
        $tableName = MovieModel::model()->getTableFullName();
        $markedCondition = array();
        $markedCondition['movie_id'] = new SQLExpression($tableName.'.id');
        $markedCondition['account_id'] = $this->accountID;
        $markedCondition['mark'] = $mark;
        $markCondition = MovieUserMarkModel::query()->addExpression('movie_id')->find($markedCondition);
        $basicCondition = ConditionBuilder::build()->exists($markCondition);
        return $basicCondition;
    }
    
    /**
     * @param mixed $condition
     * @return \X\Service\XDatabase\Core\SQL\Condition\Builder
     */
    private function getExtenCondition( $condition ) {
        $con = ConditionBuilder::build();
        if ( isset($condition['date']) ) {
            $con->between('date', $condition['date'].'-01-01', $condition['date'].'-12-31');
        }
        if ( isset($condition['region']) ) {
            $con->equals('region_id', $condition['region']);
        }
        if ( isset($condition['language']) ) {
            $con->equals('language_id', $condition['language']);
        }
        if ( isset( $condition['director'] ) ) {
            $directors = MovieDirectorModel::model()->findAll(array('name'=>$condition['director']));
            foreach ( $directors as $index => $director ) {
                $directors[$index] = $director->id;
            }
            $directorCondition = array();
            $directorCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $directorCondition['director_id'] = $directors;
            $directorCondition = MovieDirectorMapModel::query()->addExpression('movie_id')->find($directorCondition);
            $con->exists($directorCondition);
        }
        if ( isset( $condition['actor'] ) ) {
            $actors = MovieActorModel::model()->findAll(array('name'=>$condition['actor']));
            foreach ( $actors as $index => $actor ) {
                $actors[$index] = $actor->id;
            }
            $actorCondition = array();
            $actorCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $actorCondition['actor_id'] = $actors;
            $actorCondition = MovieActorMapModel::query()->addExpression('movie_id')->find($actorCondition);
            $con->exists($actorCondition);
        }
        if ( isset($condition['category']) ) {
            $categoryCondition = array();
            $categoryCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $categoryCondition['category_id'] = $condition['category'];
            $categoryCondition = MovieCategoryMapModel::query()->addExpression('movie_id')->find($categoryCondition);
            $con->exists($categoryCondition);
        }
        if ( isset($condition['name']) ) {
            $con->includes('name', $condition['name']);
        }
        return $con;
    }
    
    /**
     * @param string $movieID
     * @param string $mark
     */
    public function mark( $movieID, $mark ) {
        $deleteCondition = array();
        $deleteCondition['movie_id'] = $movieID;
        $deleteCondition['account_id'] = $this->accountID;
        MovieUserMarkModel::model()->deleteAll($deleteCondition);
        
        if ( 0 === $mark*1 ) {
            return;
        }
        
        $markModel = new MovieUserMarkModel();
        $markModel->movie_id = $movieID;
        $markModel->account_id = $this->accountID;
        $markModel->mark = $mark;
        $markModel->save();
    }
    
    /**
     * @param string $movieID
     * @return number
     */
    public function getMark( $movieID ) {
        $condition = array();
        $condition['movie_id']      = $movieID;
        $condition['account_id']    = $this->accountID;
        $mark = MovieUserMarkModel::model()->find($condition);
        return ( null === $mark ) ? Movie::MARK_UNMARKED : (int)$mark->mark;
    }
    
    /**
     * @param string $movieID
     * @return boolean
     */
    public function isWatched( $movieID ) {
        return Movie::MARK_WATCHED === $this->getMark($movieID);
    }
    
    /**
     * @param string $movieID
     * @return boolean
     */
    public function isInterested( $movieID ) {
        return Movie::MARK_INTERESTED === $this->getMark($movieID);
    }
    
    /**
     * @param string $movieID
     * @return boolean
     */
    public function isIgnored( $movieID ) {
        return Movie::MARK_IGNORED === $this->getMark($movieID);
    }
    
    /**
     * @param integer $mark
     * @return integer
     */
    public function countMarkedFriends( $movieID, $mark ) {
        $friends = $this->account->getFriendManager()->find();
        if ( empty($friends) ) {
            return 0;
        }
        foreach ( $friends as $index => $friend ) {
            $friends[$index] = $friend->getID();
        }
        
        $condition = array();
        $condition['mark']          = $mark;
        $condition['movie_id']      = $movieID;
        $condition['account_id']    = $friends;
        $count = MovieUserMarkModel::model()->count($condition);
        return $count;
    }
    
    /**
     * @param mixed $condition
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function findMarkedFriends( $condition ) {
        $friendManager = $this->account->getFriendManager();
        $markConditon = array();
        $markConditon['movie_id']   = $condition->condition['movie_id'];
        $markConditon['mark']       = $condition->condition['mark'];
        $markConditon['account_id'] = new SQLExpression($friendManager->getFindAttributeName('account_friend'));;
        $markConditon = MovieUserMarkModel::query()->addExpression('id')->findAll($markConditon);
        $markConditon = ConditionBuilder::build()->exists($markConditon);
        $condition->condition = $markConditon;
        $accounts = $friendManager->find($condition);
        return $accounts;
    }
    
    /**
     * @return number
     */
    public function getScore( $movieID ) {
        $condition = array('movie_id'=>$movieID, 'account_id'=>$this->accountID);
        $rate = MovieUserRateModel::model()->find($condition);
        if ( null === $rate ) {
            return 0;
        }
        return $rate->score*1;
    }
    
    /**
     * @param string $movieID
     * @param string $score
     */
    public function setScore( $movieID, $score ) {
        $condition = array('movie_id'=>$movieID, 'account_id'=>$this->accountID);
        $rate = MovieUserRateModel::model()->find($condition);
        if ( null === $rate ) {
            $rate = new MovieUserRateModel();
        }
        $rate->movie_id = $movieID;
        $rate->score = $score;
        $rate->account_id = $this->accountID;
        $rate->save();
    }
    
    /**
     * @param string $movieID
     * @param string $content
     * @return \X\Module\Movie\Service\Movie\Core\Instance\ShortComment
     */
    public function addShortComment( $movieID ) {
        $comment = new MovieShortCommentModel();
        $comment->movie_id = $movieID;
        $comment->commented_at = date('Y-m-d H:i:s');
        $comment->commented_by = $this->accountID;
        $comment->parent_id = 0;
        return new ShortComment($comment);
    }
    
    /**
     * @param string $accountID
     * @param string $movieID
     * @param string $comment
     * @param string $view
     */
    public function sendWatchMovieInvitation($accountID, $movieID, $comment, $view) {
        $invitation = new MovieInvitationModel();
        $invitation->account_id = $accountID;
        $invitation->invited_at = date('Y-m-d H:i:s');
        $invitation->inviter_id = $this->accountID;
        $invitation->movie_id = $movieID;
        $invitation->comment = $comment;
        $invitation->save();
        
        $this->account->getNotificationManager()->create()
            ->setView($view)
            ->setSourceDataModel($invitation)
            ->setRecipiendID($accountID)
            ->send();
    }
    
    /**
     * @param unknown $invitationID
     * @param unknown $answer
     * @param unknown $comment
     * @param unknown $view
     */
    public function answerWatchMovieInvitation( $notificationID, $answer, $comment, $view ) {
        $notification = $this->account->getNotificationManager()->get($notificationID);
        $invitationInfo = $notification->getData();
        
        /* @var $invitation MovieInvitationModel */
        $invitation = MovieInvitationModel::model()->findByPrimaryKey($invitationInfo['id']);
        $invitation->answer = (self::INVITATION_ANSWER_YES===$answer*1) ? self::INVITATION_ANSWER_YES : self::INVITATION_ANSWER_NO;
        $invitation->answered_at = date('Y-m-d H:i:s');
        $invitation->answer_comment = $comment;
        $invitation->save();
        
        $notification->close();
        
        $this->account->getNotificationManager()->create()
            ->setView($view)
            ->setSourceDataModel($invitation)
            ->setRecipiendID($invitation->inviter_id)
            ->send();
    }
    
    /**
     * @var integer
     */
    const INVITATION_ANSWER_YES = 1;
    
    /**
     * @var integer
     */
    const INVITATION_ANSWER_NO = 2;
}