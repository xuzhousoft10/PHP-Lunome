<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Core\X;
use X\Module\Movie\Service\Movie\Core\Model\MovieCharacterModel;
use X\Service\QiNiu\Service as QiNiuService;
use X\Module\Movie\Service\Movie\Core\Model\MovieCharacterCommentModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieCharacterVoteModel;
use X\Module\Movie\Service\Movie\Core\Model\MovieCharacterFavouriteModel;
use X\Module\Movie\Service\Movie\Util\InteractionInstance;
/**
 * 
 */
class Character extends InteractionInstance {
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
     * @return string
     */
    public function getCommentModel() { 
        return MovieCharacterCommentModel::getClassName(); 
    }
    
    /**
     * @return string
     */
    public function getVoteModel() {
        return MovieCharacterVoteModel::getClassName();
    }
    
    /**
     * @return string
     */
    public function getFavouriteModel() {
        return MovieCharacterFavouriteModel::getClassName();
    }
}