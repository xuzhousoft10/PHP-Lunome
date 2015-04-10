<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Core\X;
use X\Module\Movie\Service\Movie\Core\Model\MovieCharacterModel;
use X\Service\QiNiu\Service as QiNiuService;
use X\Util\Service\Manager\ShortCommentManager;
use X\Module\Movie\Service\Movie\Core\Model\MovieCharacterCommentModel;
use X\Util\Service\Manager\VoteManager;
use X\Module\Movie\Service\Movie\Core\Model\MovieCharacterVoteModel;
/**
 * 
 */
class Character {
    /**
     * @var MovieCharacterModel
     */
    private $model = null;
    
    /**
     * @param MovieCharacterModel $categoryModel
     */
    public function __construct( $model ) {
        $this->model = $model;
    }
    
    /**
     * @param string $name
     */
    public function get($name) {
        return $this->model->get($name);
    }
    
    /**
     * @return string
     */
    public function getPhotoURL() {
        $photoURL = $this->model->photo_url;
        if ( empty($photoURL) ) {
            $assetsURL = X::system()->getConfiguration()->get('assets-base-url');
            $photoURL = $assetsURL.'/image/movie_default_character_photo.jpg';
        }
        
        return $photoURL;
    }
    
    /**
     * @return string
     */
    public function getName() {
        return $this->model->name;
    }
    
    /**
     * @var string
     */
    private $photoPath = null;
    
    /**
     * @param string $path
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Character
     */
    public function setPhoto( $path ) {
        $this->photoPath = $path;
        return $this;
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Character
     */
    public function set( $name, $value ) {
        $this->model->set($name, $value);
        return $this;
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Character
     */
    public function save() {
        $this->model->save();
        if ( null !== $this->photoPath ) {
            /* @var $qiniu QiniuService */
            $qiniu = X::system()->getServiceManager()->get(QiNiuService::getServiceName());
            $qiniu->setBucket('lunome-movie-characters');
            $qiniu->putFile($this->photoPath, null, $this->model->id);
        }
        return $this;
    }
    
    /**
     * @var ShortCommentManager
     */
    private $commentManager = null;
    
    /**
     * @return \X\Util\Service\Manager\ShortCommentManager
     */
    public function getCommentManager() {
        if ( null === $this->commentManager ) {
            $this->commentManager = new ShortCommentManager($this, MovieCharacterCommentModel::getClassName());
        }
        return $this->commentManager;
    }
    
    /**
     * @var VoteManager
     */
    private $voteManger = null;
    
    /**
     * @return \X\Util\Service\Manager\VoteManager
     */
    public function getVoteManager() {
        if ( null === $this->voteManger ) {
            $this->voteManger = new VoteManager($this, MovieCharacterVoteModel::getClassName());
        }
        return $this->voteManger;
    }
}