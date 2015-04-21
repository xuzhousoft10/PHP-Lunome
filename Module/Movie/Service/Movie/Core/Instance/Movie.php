<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Core\X;
use X\Module\Movie\Service\Movie\Core\Model\MovieModel;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Movie\Service\Movie\Core\Model\MovieUserMarkModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieCategoryMapModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieCategoryModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieDirectorMapModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieDirectorModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieActorMapModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieActorModel;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Service\XDatabase\Core\SQL\Util\Expression as SQLExpression;
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Movie\Service\Movie\Core\Manager\ClassicDialogueManager;
use X\Module\Movie\Service\Movie\Core\Manager\PosterManager;
use X\Module\Movie\Service\Movie\Core\Manager\CharacterManager;
use X\Module\Movie\Service\Movie\Core\Manager\ShortCommentManager;
use X\Util\Service\Instance\Model;
use X\Module\Movie\Service\Movie\Core\Manager\NewsManager;
use X\Module\Movie\Service\Movie\Core\Manager\CriticismManager;
/**
 * 
 */
class Movie extends Model {
    /**
     * @return string
     */
    protected static function getModelClass() {
        return get_class(MovieModel::model());
    }
    
    /**
     * @var MovieModel
     */
    private $movieModel = null;
    
    /**
     * @var MovieService
     */
    private $movieService = null;
    
    /**
     * @var string
     */
    private $movieID = null;
    
    /**
     * @param MovieModel $movieModel
     */
    public function __construct( $movieModel ) {
        $this->movieModel = $movieModel;
        $this->movieID = $movieModel->id;
        $this->movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
    }
    
    /**
     * @var integer
     */
    const MARK_UNMARKED     = 0;
    
    /**
     * @var integer
     */
    const MARK_INTERESTED   = 1;
    
    /**
     * @var integer
     */
    const MARK_WATCHED      = 2;
    
    /**
     * @var integer
     */
    const MARK_IGNORED      = 3;
    
    /**
     * @return boolean
     */
    public function hasCover() {
        return (int)$this->movieModel->has_cover === 1;
    }
    
    /**
     * @param string $name
     * @return mixed
     */
    public function get( $name ) {
        return $this->movieModel->get($name);
    }
    
    /**
     * @param string $refresh
     * @return string
     */
    public function getCoverURL($refresh=false) {
        $url = null;
        if ( $this->hasCover() ) {
            $url = 'http://7xawql.com1.z0.glb.clouddn.com/'.$this->movieID;
            if ( $refresh ) {
                $url .= '?rand='.uniqid();
            }
        } else {
            $url = X::system()->getConfiguration()->get('assets-base-url');
            $url = $url.'/image/movie_default_cover.jpg';
        }
        return $url;
    }
    
    /**
     * @param integer $mark
     * @return number
     */
    public function countMarked( $mark ) {
        $condition = array();
        $condition['mark']      = $mark;
        $condition['movie_id']  = $this->movieID;
        $count = MovieUserMarkModel::model()->count($condition);
        return $count;
    }
    
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Region
     */
    public function getRegion() {
        return $this->movieService->getRegionManger()->get($this->get('region_id'));
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Category
     */
    public function getCategories() {
        $categories = MovieCategoryMapModel::model()->findAll(array('movie_id'=>$this->movieID));
        if ( 0 === count($categories) ) {
            return array();
        }
        
        foreach ( $categories as $index => $category ) {
            $categories[$index] = $category->category_id;
        }
        $categories = MovieCategoryModel::model()->findAll(array('id'=>$categories));
        foreach ( $categories as $index => $category ) {
            $categories[$index] = new Category($category);
        }
        return $categories;
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Language
     */
    public function getLanguage() {
        return $this->movieService->getLanguageManager()->get($this->get('language_id'));
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Director
     */
    public function getDirectors() {
        $directors = MovieDirectorMapModel::model()->findAll(array('movie_id'=>$this->movieID));
        if ( empty($directors) ) {
            return array();
        }
        
        foreach ( $directors as $index => $director ) {
            $directors[$index] = $director->director_id;
        }
        $directors = MovieDirectorModel::model()->findAll(array('id'=>$directors));
        foreach ( $directors as $index => $director ) {
            $directors[$index] = new Director($director);
        }
        return $directors;
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Actor
     */
    public function getActors() {
        $actors = MovieActorMapModel::model()->findAll(array('movie_id'=>$this->movieID));
        if ( empty($actors) ) {
            return array();
        }
        
        foreach ( $actors as $index => $actor ) {
            $actors[$index] = $actor->actor_id;
        }
        $actors = MovieActorModel::model()->findAll(array('id'=>$actors));
        foreach ( $actors as $index => $actor ) {
            $actors[$index] = new Actor($actor);
        }
        return $actors;
    }
    
    /**
     * @param mixed $condition
     * @return \X\Module\Account\Service\Account\Core\Instance\Account
     */
    public function findMarkedAccounts( $condition ) {
        /* @var $accountService AccountService */
        $accountService = X::system()->getServiceManager()->get(AccountService::getServiceName());
        
        $markConditon = array();
        $markConditon['movie_id']   = $this->movieID;
        $markConditon['mark']       = $condition->condition['mark'];
        $markConditon['account_id'] = new SQLExpression($accountService->getFindAttributeName('id'));;
        $markConditon = MovieUserMarkModel::query()->addExpression('id')->findAll($markConditon);
        $markConditon = ConditionBuilder::build()->exists($markConditon);
        $condition->condition = $markConditon;
        $accounts = $accountService->find($condition);
        return $accounts;
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Manager\ClassicDialogueManager
     */
    public function getClassicDialogueManager() {
        return new ClassicDialogueManager($this->movieModel);
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Manager\PosterManager
     */
    public function getPosterManager() {
        return new PosterManager($this->movieModel);
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Manager\CharacterManager
     */
    public function getCharacterManager() {
        return new CharacterManager($this->movieModel);
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Manager\ShortCommentManager
     */
    public function getShortCommentManager() {
        return new ShortCommentManager($this->movieModel);
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Manager\NewsManager
     */
    public function getNewsManager() {
        return new NewsManager($this);
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Manager\CriticismManager
     */
    public function getCriticismManager() {
        return new CriticismManager($this);
    }
}