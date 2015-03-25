<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Core\X;
use X\Module\Movie\Service\Movie\Core\Model\MovieCharacterModel;
use X\Service\QiNiu\Service as QiNiuService;
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
        return 'http://7te9pc.com1.z0.glb.clouddn.com/'.$this->model->id;
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
}