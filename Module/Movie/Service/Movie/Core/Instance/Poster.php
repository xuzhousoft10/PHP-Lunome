<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Core\X;
use X\Module\Movie\Service\Movie\Core\Model\MoviePosterModel;
use X\Service\QiNiu\Service as QiniuService;
/**
 * 
 */
class Poster {
    /**
     * @var MoviePosterModel
     */
    private $model = null;
    
    /**
     * @param MoviePosterModel $model
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
     * @var string
     */
    private $imagePath = null;
    
    /**
     * @param string $path
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Poster
     */
    public function setImage($path) {
        $this->imagePath = $path;
        return $this;
    }
    
    /**
     * @param string $name
     * @param mixed $value
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Poster
     */
    public function set($name, $value) {
        $this->model->set($name, $value);
        return $this;
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Poster
     */
    public function save() {
        $this->model->save();
        
        /* @var $qiniu QiniuService */
        $qiniu = X::system()->getServiceManager()->get(QiniuService::getServiceName());
        $qiniu->setBucket('lunome-movie-posters');
        $qiniu->putFile($this->imagePath, null, $this->model->id);
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getURL() {
        return 'http://7sbyuj.com1.z0.glb.clouddn.com/'.$this->model->id;
    }
}