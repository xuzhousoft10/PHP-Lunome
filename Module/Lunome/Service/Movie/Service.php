<?php
/**
 * This file implements the service Movie
 */
namespace X\Module\Lunome\Service\Movie;

/**
 * Use statement
 */
use X\Core\X;
use X\Module\Lunome\Util\Service\Media;
use X\Module\Lunome\Model\Movie\MovieRegionModel;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Lunome\Model\Movie\MovieLanguageModel;
use X\Module\Lunome\Model\Movie\MovieCategoryModel;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Service\XDatabase\Core\SQL\Expression as SQLExpression;
use X\Module\Lunome\Model\Movie\MovieModel;
use X\Module\Lunome\Model\Movie\MovieCategoryMapModel;
use X\Service\XDatabase\Core\Exception as DBException;
use X\Module\Lunome\Model\Movie\MovieShortCommentModel;
use X\Module\Lunome\Model\Movie\MovieClassicDialogueModel;
use X\Module\Lunome\Model\Movie\MovieUserRateModel;
use X\Module\Lunome\Model\Movie\MoviePosterModel;
use X\Service\QiNiu\Service as QiniuService;
use X\Module\Lunome\Model\Movie\MovieDirectorMapModel;
use X\Module\Lunome\Model\People\PeopleModel;
use X\Module\Lunome\Model\Movie\MovieActorMapModel;
use X\Module\Lunome\Model\Movie\MovieCharacterModel;
use X\Module\Lunome\Model\Movie\MovieUserMarkModel;
use X\Module\Lunome\Model\Account\AccountFriendshipModel;
use X\Module\Lunome\Model\Account\AccountInformationModel;
use X\Service\XDatabase\Core\SQL\Builder as SQLBuilder;
use X\Module\Lunome\Model\Movie\MovieInvitationModel;
use X\Service\XDatabase\Core\SQL\Condition\Condition as SQLCondition;

/**
 * The service class
 */
class Service extends Media {
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Media::getMediaName()
     */
    public function getMediaName() {
        return '电影';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Media::getMarkNames()
     */
    public function getMarkNames() {
        return array(
            self::MARK_UNMARKED     => '未标记',
            self::MARK_INTERESTED   => '想看',
            self::MARK_WATCHED      => '已看',
            self::MARK_IGNORED      => '忽略',
        );
    }
    
    /**
     * 根据ID返回相应Media的封面图片URL
     * @param unknown $id
     * @return string
     */
    public function getCoverURL( $id ) {
        $path = 'http://7sbycx.com1.z0.glb.clouddn.com/'.$id.'.jpg';
        return $path;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Service\Markable::getMediaModelName()
     */
    protected function getMediaModelName() {
        return 'X\\Module\\Lunome\\Model\\Movie\\MovieModel';
    }
    
    /**
     * @param unknown $condition
     */
    protected function getExtenCondition( $condition ) {
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
            $directors = PeopleModel::model()->findAll(array('name'=>$condition['director']));
            foreach ( $directors as $index => $director ) {
                $directors[$index] = $director->id;
            }
            $directorCondition = array();
            $directorCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $directorCondition['director_id'] = $directors;
            $directorCondition = MovieDirectorMapModel::query()->activeColumns(array('movie_id'))->find($directorCondition);
            $con->exists($directorCondition);
        }
        if ( isset( $condition['actor'] ) ) {
            $actors = PeopleModel::model()->findAll(array('name'=>$condition['actor']));
            foreach ( $actors as $index => $actor ) {
                $actors[$index] = $actor->id;
            }
            $actorCondition = array();
            $actorCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $actorCondition['actor_id'] = $directors;
            $actorCondition = MovieActorMapModel::query()->activeColumns(array('movie_id'))->find($actorCondition);
            $con->exists($actorCondition);
        }
        if ( isset($condition['category']) ) {
            $categoryCondition = array();
            $categoryCondition['movie_id'] = new SQLExpression(MovieModel::model()->getTableFullName().'.id');
            $categoryCondition['category_id'] = $condition['category'];
            $categoryCondition = MovieCategoryMapModel::query()->activeColumns(array('movie_id'))->find($categoryCondition);
            $con->exists($categoryCondition);
        }
        if ( isset($condition['name']) ) {
            $con->includes('name', $condition['name']);
        }
        return $con;
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieRegionModel[]
     */
    public function getRegions() {
        $criteria = new Criteria();
        $criteria->addOrder('count', 'DESC');
        $regions = MovieRegionModel::model()->findAll($criteria);
        return $regions;
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieRegionModel
     */
    public function getRegionById( $regionId ) {
        return MovieRegionModel::model()->findByPrimaryKey($regionId);
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieLanguageModel
     */
    public function getLanguageById( $languageId ) {
        return MovieLanguageModel::model()->findByPrimaryKey($languageId);
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieCategoryModel[]
     */
    public function getCategoriesByMovieId( $movieId ) {
        $categories = MovieCategoryMapModel::model()->findAll(array('movie_id'=>$movieId));
        if ( 0 === count($categories) ) {
            return array();
        }
        
        foreach ( $categories as $index => $category ) {
            $categories[$index] = $category->category_id;
        }
        $categories = MovieCategoryModel::model()->findAll(array('id'=>$categories));
        return $categories;
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieLanguageModel[]
     */
    public function getLanguages() {
        $criteria = new Criteria();
        $criteria->addOrder('count', 'DESC');
        $languages = MovieLanguageModel::model()->findAll($criteria);
        return $languages;
    }
    
    /**
     * @return \X\Module\Lunome\Model\Movie\MovieCategoryModel[]
     */
    public function getCategories() {
        $criteria = new Criteria();
        $criteria->addOrder('count', 'DESC');
        $languages = MovieCategoryModel::model()->findAll($criteria);
        return $languages;
    }
    
    /**
     * @param unknown $id
     * @param unknown $categories
     */
    public function setCategories( $id, $categories ) {
        $oldCategoryMaps = MovieCategoryMapModel::model()->findAll(array('movie_id'=>$id));
        foreach ( $oldCategoryMaps as $oldCategoryMap ) {
            $oldCategory = MovieCategoryModel::model()->findByPrimaryKey($oldCategoryMap->category_id);
            $oldCategory->count --;
            $oldCategory->save();
            
            $oldCategoryMap->delete();
        }
        
        foreach ( $categories as $category ) {
            $map = new MovieCategoryMapModel();
            $map->category_id = $category;
            $map->movie_id = $id;
            $map->save();
            
            $newCategory = MovieCategoryModel::model()->findByPrimaryKey($category);
            $newCategory->count ++;
            $newCategory->save();
        }
    }
    
    /**
     *
     * @param unknown $id
     * @param unknown $media
     * @return \X\Module\Lunome\Model\Movie\MovieModel
     */
    public function update( $movie, $id=null ) {
        $mediaModelName = $this->getMediaModelName();
        $language = array('new'=>$movie['language_id']);
        $region   = array('new'=>$movie['region_id']);
        
        /* @var $mediaModel \X\Util\Model\Basic */
        if ( empty($id) ) {
            $mediaModel = new $mediaModelName();
        } else {
            $mediaModel = $mediaModelName::model()->findByPrimaryKey($id);
            $language['old'] = $mediaModel->language_id;
            $region['old'] = $mediaModel->region_id;
        }
        
        foreach ( $movie as $attr => $value ) {
            $mediaModel->set($attr, $value);
        }
    
        try {
            $mediaModel->save();
            
            if ( !empty($language['new']) ) {
                $languageModel = MovieLanguageModel::model()->findByPrimaryKey($language['new']);
                $languageModel->count ++;
                $languageModel->save();
            }
            
            if ( !empty($region['new']) ) {
                $regionModel = MovieRegionModel::model()->findByPrimaryKey($region['new']);
                $regionModel->count ++;
                $regionModel->save();
            }
            
            if ( isset($language['old']) && !empty($language['old']) ) {
                $languageModel = MovieLanguageModel::model()->findByPrimaryKey($language['old']);
                $languageModel->count --;
                $languageModel->save();
            }
            
            if ( !empty($region['old']) && !empty($region['old']) ) {
                $regionModel = MovieRegionModel::model()->findByPrimaryKey($region['old']);
                $regionModel->count --;
                $regionModel->save();
            }
            
            $this->logAction($this->getActionName('add'), $mediaModel->id);
        } catch ( DBException $e ){}
        return $mediaModel;
    }
    
    /**
     * @param unknown $id
     * @param unknown $content
     * @return \X\Module\Lunome\Model\Movie\MovieShortCommentModel
     */
    public function addShortComment( $id, $content ) {
        $model = new MovieShortCommentModel();
        $model->movie_id = $id;
        $model->content = $content;
        $model->commented_at = date('Y-m-d H:i:s', time());
        $model->commented_by = $this->getCurrentUserId();
        $model->parent_id = '00000000-0000-0000-0000-000000000000';
        $model->save();
        return $model;
    }
    
    /**
     * @param unknown $id
     * @param string $parent
     * @return Ambigous <multitype:\X\Service\XDatabase\Core\ActiveRecord\XActiveRecord , multitype:>
     */
    public function getShortComments( $id, $parent=null, $position, $length ) {
        $condition = array();
        $condition['movie_id'] = $id;
        $condition['parent_id'] = (null===$parent)?'00000000-0000-0000-0000-000000000000':$parent;
        
        $criteria = new Criteria();
        $criteria->limit = $length;
        $criteria->position = $position;
        $criteria->condition = $condition;
        $criteria->addOrder('commented_at', 'DESC');
        
        $comments = MovieShortCommentModel::model()->findAll($criteria);
        return $comments;
    }
    
    /**
     * @param unknown $id
     * @param string $parent
     * @return Ambigous <multitype:\X\Service\XDatabase\Core\ActiveRecord\XActiveRecord , multitype:>
     */
    public function getShortCommentsFromFriends( $movieID, $parent=null, $position, $length ) {
        $currentUserID = $this->getCurrentUserId();
        
        $condition = ConditionBuilder::build();
        $condition->is('movie_id', $movieID);
        $condition->is('parent_id', (null===$parent)?'00000000-0000-0000-0000-000000000000':$parent);
        
        $friendCondition = array();
        $releatedAttrName = MovieShortCommentModel::model()->getAttributeQueryName('commented_by');
        $friendCondition['account_friend'] = new SQLExpression($releatedAttrName);
        $friendCondition = AccountFriendshipModel::query()->activeColumns(array('id'))->find($friendCondition);
        $condition->exists($friendCondition);
        
        $criteria = new Criteria();
        $criteria->limit = $length;
        $criteria->position = $position;
        $criteria->condition = $condition;
        $criteria->addOrder('commented_at', 'DESC');
        
        $comments = MovieShortCommentModel::model()->findAll($criteria);
        return $comments;
    }
    
    /**
     * @param unknown $id
     * @param string $parent
     * @return Ambigous <multitype:\X\Service\XDatabase\Core\ActiveRecord\XActiveRecord , multitype:>
     */
    public function countShortCommentsFromFriends( $movieID, $parent=null ) {
        $currentUserID = $this->getCurrentUserId();
    
        $condition = ConditionBuilder::build();
        $condition->is('movie_id', $movieID);
        $condition->is('parent_id', (null===$parent)?'00000000-0000-0000-0000-000000000000':$parent);
    
        $friendCondition = array();
        $releatedAttrName = MovieShortCommentModel::model()->getAttributeQueryName('commented_by');
        $friendCondition['account_friend'] = new SQLExpression($releatedAttrName);
        $friendCondition = AccountFriendshipModel::query()->activeColumns(array('id'))->find($friendCondition);
        $condition->exists($friendCondition);
        
        $count = MovieShortCommentModel::model()->count($condition);
        return $count;
    }
    
    /**
     * @param unknown $id
     * @param string $parent
     * @return Ambigous <multitype:\X\Service\XDatabase\Core\ActiveRecord\XActiveRecord , multitype:>
     */
    public function countShortComments( $id, $parent=null ) {
        $condition = array();
        $condition['movie_id'] = $id;
        $condition['parent_id'] = (null===$parent)?'00000000-0000-0000-0000-000000000000':$id;
        
        $count = MovieShortCommentModel::model()->count($condition);
        return $count;
    }
    
    /**
     * @param unknown $id
     * @param unknown $position
     * @param unknown $length
     * @return \X\Module\Lunome\Model\Movie\MovieClassicDialogueModel[]
     */
    public function getClassicDialogues( $id, $position, $length ) {
        $criteria = new Criteria();
        $criteria->condition = array('movie_id'=>$id);
        $criteria->position = $position;
        $criteria->limit = $length;
        $dialogues = MovieClassicDialogueModel::model()->findAll($criteria);
        return $dialogues;
    }
    
    /**
     * @param unknown $movieID
     * @return number
     */
    public function countClasicDialogues( $movieID ) {
        return MovieClassicDialogueModel::model()->count(array('movie_id'=>$movieID));
    }
    
    /**
     * @param unknown $id
     * @param unknown $content
     */
    public function addClassicDialogues( $id, $content ) {
        $dialogues = new MovieClassicDialogueModel();
        $dialogues->movie_id = $id;
        $dialogues->content = $content;
        $dialogues->save();
    }
    
    /**
     * @param unknown $id
     * @param unknown $score
     */
    public function setRateScore( $id, $score ) {
        $currentUserId = $this->getCurrentUserId();
        $condition = array('movie_id'=>$id, 'account_id'=>$currentUserId);
        $rate = MovieUserRateModel::model()->find($condition);
        if ( null === $rate ) {
            $rate = new MovieUserRateModel();
        }
        $rate->movie_id = $id;
        $rate->score = $score;
        $rate->account_id = $this->getCurrentUserId();
        $rate->save();
    }
    
    /**
     * @param unknown $id
     */
    public function getRateScore( $id ) {
        $currentUserId = $this->getCurrentUserId();
        $condition = array('movie_id'=>$id, 'account_id'=>$currentUserId);
        $rate = MovieUserRateModel::model()->find($condition);
        if ( null === $rate ) {
            return 0;
        }
        return $rate->score*1;
    }
    
    /**
     * @param unknown $id
     * @param unknown $path
     * @param unknown $type
     */
    public function addPoster( $id, $path ) {
        $poster = new MoviePosterModel();
        $poster->movie_id = $id;
        $poster->save();
        
        /* @var $qiniu QiniuService */
        $qiniu = X::system()->getServiceManager()->get(QiniuService::getServiceName());
        $qiniu->setBucket('lunome-movie-posters');
        $qiniu->putFile($path, null, $poster->id);
    }
    
    /**
     * @param unknown $id
     * @param unknown $position
     * @param unknown $length
     * @return \X\Module\Lunome\Model\Movie\MoviePosterModel[]
     */
    public function getPosters( $id, $position, $length ) {
        $criteria = new Criteria();
        $criteria->condition = array('movie_id'=>$id);
        $criteria->position = $position;
        $criteria->limit = $length;
        $dialogues = MoviePosterModel::model()->findAll($criteria);
        return $dialogues;
    }
    
    /**
     * @param unknown $movieID
     * @return number
     */
    public function countPosters( $movieID ) {
        return MoviePosterModel::model()->count(array('movie_id'=>$movieID));
    }
    
    /**
     * @param unknown $id
     * @return string
     */
    public function getPosterUrlById( $id ) {
        return 'http://7sbyuj.com1.z0.glb.clouddn.com/'.$id;
    }
    
    /**
     * @param unknown $id
     * @return \X\Module\Lunome\Model\People\PeopleModel[]
     */
    public function getDirectors( $id ) {
        $directors = MovieDirectorMapModel::model()->findAll(array('movie_id'=>$id));
        if ( empty($directors) ) {
            return array();
        }
        
        foreach ( $directors as $index => $director ) {
            $directors[$index] = $director->director_id;
        }
        $directors = PeopleModel::model()->findAll(array('id'=>$directors));
        return $directors;
    }
    
    /**
     * @param unknown $id
     */
    public function getActors( $id ) {
        $actors = MovieActorMapModel::model()->findAll(array('movie_id'=>$id));
        if ( empty($actors) ) {
            return array();
        }
        
        foreach ( $actors as $index => $actor ) {
            $actors[$index] = $actor->actor_id;
        }
        $directors = PeopleModel::model()->findAll(array('id'=>$actors));
        return $directors;
    }
    
    /**
     * @param unknown $movieId
     * @param unknown $characterInfo
     * @return \X\Module\Lunome\Model\Movie\MovieCharacterModel
     */
    public function addCharacter( $movieId, $characterInfo, $image=null ) {
        $character = new MovieCharacterModel();
        $character->movie_id = $movieId;
        $character->name = $characterInfo['name'];
        $character->description = $characterInfo['description'];
        $character->save();
        
        if ( null !== $image ) {
            /* @var $qiniu QiniuService */
            $qiniu = X::system()->getServiceManager()->get(QiniuService::getServiceName());
            $qiniu->setBucket('lunome-movie-characters');
            $qiniu->putFile($image, null, $character->id);
        }
        return $character;
    }
    
    /**
     * @param unknown $movieId
     * @param unknown $offset
     * @param unknown $length
     * @return \X\Module\Lunome\Model\Movie\MovieCharacterModel[]
     */
    public function getCharacters( $movieId, $offset, $length ) {
        $criteria = new Criteria();
        $criteria->condition = array('movie_id'=>$movieId);
        $criteria->position = $offset;
        $criteria->limit = $length;
        $characters = MovieCharacterModel::model()->findAll($criteria);
        return $characters;
    }
    
    /**
     * @param unknown $movieID
     * @return number
     */
    public function countCharacters( $movieID ) {
        return MovieCharacterModel::model()->count(array('movie_id'=>$movieID));
    }
    
    /**
     * @param unknown $characterId
     * @return string
     */
    public function getCharacterUrlById( $characterId ) {
        return 'http://7te9pc.com1.z0.glb.clouddn.com/'.$characterId;
    }
    
    /**
     * 统计全网用户对指定电影做指定标记的数量。
     * @param string $movieID 查询的电影的ID
     * @param integer $mark 查询的标记代码。标记宏为 Service::MARK_*
     * @return integer
     */
    public function countMarkedUsers( $movieID, $mark ) {
        $condition = array();
        $condition['mark']      = $mark;
        $condition['movie_id']  = $movieID;
        $count = MovieUserMarkModel::model()->count($condition);
        return $count;
    }
    
    /**
     * 统计当前用户的好友对指定电影标记的数量。
     * @param string $movieID 查询的电影的ID
     * @param string $mark 查询的标记代码。标记宏为 Service::MARK_*
     * @return integer
     */
    public function countMarkedFriends( $movieID, $mark ) {
        $currentUserID = $this->getCurrentUserId();
        
        $condition = ConditionBuilder::build();
        $condition->is('mark', $mark);
        $condition->is('movie_id', $movieID);
        
        $friendCondition = array();
        $releatedAttrName = MovieUserMarkModel::model()->getAttributeQueryName('account_id');
        $friendCondition['account_friend'] = new SQLExpression($releatedAttrName);
        $friendCondition = AccountFriendshipModel::query()->activeColumns(array('id'))->find($friendCondition);
        $condition->exists($friendCondition);
        
        $count = MovieUserMarkModel::model()->count($condition);
        return $count;
    }
    
    /**
     * 获取指定标记的电影的账户列表。
     * @param string $movieId
     * @param integer $mark
     * @param integer $position
     * @param integer $length
     * @return \X\Module\Lunome\Model\Account\AccountInformationModel[]
     */
    public function getMarkedAccounts( $movieId, $mark, $position, $length=10 ) {
        $condition = ConditionBuilder::build();
    
        $releatedAttrName = AccountInformationModel::model()->getAttributeQueryName('account_id');
        $markConditon = array();
        $markConditon['movie_id'] = $movieId;
        $markConditon['mark'] = $mark;
        $markConditon['account_id'] = new SQLExpression($releatedAttrName);;
        $markConditon = MovieUserMarkModel::query()->activeColumns(array('id'))->findAll($markConditon);
        $condition->exists($markConditon);
        
        $criteria = new Criteria();
        $criteria->condition = $condition;
        $criteria->position = $position;
        $criteria->limit = $length;
        
        $accounts = AccountInformationModel::model()->findAll($criteria);
        return $accounts;
    }
    
    /**
     * 获取指定标记的电影的当前用户的好友列表。
     * @param string $movieId
     * @param integer $mark
     * @param integer $position
     * @param integer $length
     * @return \X\Module\Lunome\Model\Account\AccountInformationModel[]
     */
    public function getMarkedFriendAccounts(  $movieId, $mark, $position, $length=10 ) {
        $condition = ConditionBuilder::build();
        
        $releatedAttrName = AccountInformationModel::model()->getAttributeQueryName('account_id');
        $markConditon = array();
        $markConditon['movie_id'] = $movieId;
        $markConditon['mark'] = $mark;
        $markConditon['account_id'] = new SQLExpression($releatedAttrName);;
        $markConditon = MovieUserMarkModel::query()->activeColumns(array('id'))->findAll($markConditon);
        $condition->exists($markConditon);
        
        $friendCondition = array();
        $releatedAttrName = AccountInformationModel::model()->getAttributeQueryName('account_id');
        $friendCondition['account_friend'] = new SQLExpression($releatedAttrName);
        $friendCondition['account_me'] = $this->getCurrentUserId();
        $friendCondition = AccountFriendshipModel::query()->activeColumns(array('id'))->find($friendCondition);
        $condition->exists($friendCondition);
        
        $criteria = new Criteria();
        $criteria->condition = $condition;
        $criteria->position = $position;
        $criteria->limit = $length;
        
        $accounts = AccountInformationModel::model()->findAll($criteria);
        return $accounts;
    }
    
    /**
     * @param unknown $accounts
     */
    public function getInterestedMovieSetByAccounts ( $accounts, $length=0, $position=0 ) {
        $marks = SQLBuilder::build()->select()
            ->addExpression('id')
            ->addTable(MovieUserMarkModel::model()->getTableFullName(), 'mark_accounts')
            ->where(ConditionBuilder::build()
                ->is('mark', self::MARK_INTERESTED)
                ->in('account_id', $accounts)
                ->addCondition(new SQLExpression('mark_movies.id=mark_accounts.id'))
            );
        
        $markTable = MovieUserMarkModel::model()->getTableFullName();
        $sql = SQLBuilder::build()->select()
            ->addExpression('movie_id')
            ->from(array('mark_movies'=>$markTable))
            ->groupBy('movie_id')
            ->where(ConditionBuilder::build()->exists($marks))
            ->having('COUNT(`movie_id`)='.count($accounts));
        
        $criteria = new Criteria();
        $criteria->limit = $length;
        $criteria->position = $position;
        $criteria->condition = ConditionBuilder::build()->in('id', $sql);
        $movies = MovieModel::model()->findAll($criteria);
        return $movies;
    }
    
    /**
     * @param unknown $accountID
     * @param unknown $movieID
     * @param unknown $comment
     */
    public function sendMovieInvitation( $accountID, $movieID, $comment, $view ) {
        $invitation = new MovieInvitationModel();
        $invitation->account_id = $accountID;
        $invitation->invited_at = date('Y-m-d H:i:s');
        $invitation->inviter_id = $this->getCurrentUserId();
        $invitation->movie_id = $movieID;
        $invitation->comment = $comment;
        $invitation->save();
        
        $sourceModel    = 'X\\Module\\Lunome\\Model\\Movie\\MovieInvitationModel';
        $sourceId       = $invitation->id;
        $recipiendId    = $accountID;
        $this->getUserService()->sendNotification($view, $sourceModel, $sourceId, $recipiendId);
        return $invitation;
    }
    
    /**
     * @param unknown $invitationID
     * @param unknown $answer
     * @param unknown $comment
     * @param unknown $view
     */
    public function answerMovieInvitation( $invitationID, $answer, $comment, $view ) {
        /* @var $invitation \X\Module\Lunome\Model\Movie\MovieInvitationModel  */
        $invitation = MovieInvitationModel::model()->findByPrimaryKey($invitationID);
        $invitation->answer = (self::INVITATION_ANSWER_YES===$answer*1) ? self::INVITATION_ANSWER_YES : self::INVITATION_ANSWER_NO;
        $invitation->answered_at = date('Y-m-d H:i:s');
        $invitation->answer_comment = $comment;
        $invitation->save();
        
        $sourceModel    = 'X\\Module\\Lunome\\Model\\Movie\\MovieInvitationModel';
        $sourceId       = $invitation->id;
        $recipiendId    = $invitation->inviter_id;
        $this->getUserService()->sendNotification($view, $sourceModel, $sourceId, $recipiendId);
        return $invitation;
    }
    
    /**
     * @param unknown $accounts
     * @param unknown $score
     * @param string $operator
     * @return Ambigous <\X\Service\XDatabase\Core\ActiveRecord\XActiveRecord, NULL>
     */
    public function getWatchedMoviesByAccounts( $accounts, $score, $operator='=', $length=0, $position=0 ) {
        $markAccountsCondition = SQLBuilder::build()->select()
            ->addExpression('id')
            ->addTable(MovieUserMarkModel::model()->getTableFullName(), 't2')
            ->where(ConditionBuilder::build()
                ->is('t1.id', new SQLExpression('`t2`.`id`'))
                ->is('mark', self::MARK_WATCHED)
                ->in('t2.account_id', $accounts)
            );
        
        $markCondition = SQLBuilder::build()->select()
            ->addExpression('movie_id')
            ->addTable(MovieUserMarkModel::model()->getTableFullName(), 't1')
            ->groupBy('t1.movie_id')
            ->having('COUNT(t1.account_id) = '.count($accounts))
            ->where(ConditionBuilder::build()->exists($markAccountsCondition));
        
        $movieCondition = SQLBuilder::build()->select()
            ->addExpression('movie_id')
            ->addTable(MovieUserRateModel::model()->getTableFullName())
            ->groupBy('movie_id')
            ->where(ConditionBuilder::build()
                ->in('account_id', $accounts)
                ->addSigleCondition('score', $operator, $score)
                ->in('movie_id', $markCondition)
            );
        
        $criteria = new Criteria();
        $criteria->limit = $length;
        $criteria->position = $position;
        $criteria->condition = ConditionBuilder::build()->in('id', $movieCondition);
        $movies = MovieModel::model()->findAll($criteria);
        return $movies;
    }
    
    const SCORE_OPERATOR_EQUAL = SQLCondition::OPERATOR_EQUAL;
    const SCORE_OPERATOR_GREATER = SQLCondition::OPERATOR_GREATER_THAN;
    const SCORE_OPERATOR_LESS_OR_EQUAL = SQLCondition::OPERATOR_LESS_OR_EQUAL;
    
    const INVITATION_ANSWER_YES = 1;
    const INVITATION_ANSWER_NO = 2;
    
    const MARK_UNMARKED     = 0;
    const MARK_INTERESTED   = 1;
    const MARK_WATCHED      = 2;
    const MARK_IGNORED      = 3;
}