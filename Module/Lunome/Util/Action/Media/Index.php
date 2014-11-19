<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action\Media;

/**
 * Use statements
 */
use X\Library\XMath\Number;
use X\Module\Lunome\Util\Action\VisualMain;

/**
 * Visual action class
 */
abstract class Index extends VisualMain {
    protected $currentMark = 0;
    protected $currentPage = 1;
    
    /**
     *
     * @param unknown $mark
     * @param number $page
     */
    public function runAction( $mark=0, $page=1 ) {
        $this->currentMark = intval($mark);
        $this->currentPage = $page;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\VisualMain::afterRunAction()
     */
    protected function afterRunAction() {
        $this->getView()->title = sprintf('%s | Lunome', $this->getMediaService()->getMediaName());
        $this->activeMenuItem($this->getActiveMenuItem());
        
        $medias     = $this->getMediaData();
        $markInfo   = $this->getMarkInformation();
        $total      = $markInfo[$this->currentMark]['count'];
        $pager      = $this->getPagerData($total);
        $markActions= $this->getMarkActions();
        $markNames  = $this->getMediaService()->getMarkNames();
        foreach ( $markActions as $markKey => $markAction ) {
            $markActions[$markKey]['name'] = $markNames[$markKey];
        }
        
        /* Load index view */
        $name   = 'MEDIA_INDEX';
        $path   = $this->getParticleViewPath('Util/Media/Index');
        $option = array();
        $data   = array(
            'medias'        => $medias, 
            'marks'         => $markInfo, 
            'pager'         => $pager, 
            'activeMark'    => $this->currentMark, 
            'markActions'   => $markActions,
            'mediaType'     => strtolower($this->getMediaType()),
            'mediaTypeName' => $this->getMediaService()->getMediaName(),
        );
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        $name   = 'MEDIA_TOO_BAR';
        $path   = $this->getParticleViewPath('Util/Media/ToolBar');
        $option = array();
        $data   = array(
            'mediaType'     => strtolower($this->getMediaType()),
        );
        $this->getView()->loadParticle($name, $path, $option, $data);
        
        parent::afterRunAction();
    }
    
    /**
     * 
     * @return array
     */
    protected function getMediaData() {
        $pageSize   = $this->getPageSize();
        $condition  = array();
        $length     = $pageSize;
        $position   = $pageSize * ($this->currentPage-1);
        if ( empty($this->currentMark) ) {
            $medias = $this->getMediaService()->getUnmarked($condition, $length, $position);
        } else {
            $medias = $this->getMediaService()->getMarked($this->currentMark, $length, $position);
        }
        
        /* 填充封面图片信息。 */
        foreach ( $medias as $index => $media ) {
            if ( 0 === $media['has_cover']*1 ) {
                $medias[$index]['cover'] = $this->getMediaService()->getMediaDefaultCoverURL();
            } else {
                $medias[$index]['cover'] = $this->getMediaService()->getMediaCoverURL($media['id']);
            }
        }
        
        return $medias;
    }
    
    /**
     * Setup the pager data.
     * 
     * @param unknown $current
     * @param unknown $total
     * @param unknown $params
     */
    protected function getPagerData($total) {
        $current    = $this->currentPage;
        $params     = array('mark'=>$this->currentMark);
        
        $pageSize   = $this->getPageSize();
        $total      = (0!=$total && 0===$total%$pageSize) ? $total/$pageSize : intval($total/$pageSize)+1;
        
        $pager = array();
        $pager['current']   = $current;
        $pager['total']     = $total;
        $pager['canPrev']   = ( 1 < $current );
        $pager['canNext']   = ( $total > $current );
        $pager['prev']      = $pager['canPrev'] ? $current-1 : 1;
        $pager['next']      = $pager['canNext'] ? $current + 1 : $total;
        $pager['items']     = Number::getRound($current, $this->getPageItemCount(), 1, $total);
        $pager['params']    = $params;
        return $pager;
    }
    
    protected function getPageSize() {
        return 20;
    }
    
    protected function getPageItemCount() {
        return 15;
    }
    
    protected function getMediaType() {
        $class = explode('\\', get_class($this));
        array_pop($class);
        $media = array_pop($class);
        return $media;
    }
    
    /**
     * @return \X\Module\Lunome\Util\Service\Media
     */
    protected function getMediaService() {
        return $this->getService($this->getMediaType());
    }
    
    /**
     *
     */
    protected function getActiveMenuItem(){
        $class = new \ReflectionClass($this);
        $activeItem = $class->getConstant(sprintf('MENU_ITEM_%s', strtoupper($this->getMediaType())));
        return $activeItem;
    }
    
    /**
     * @return array
     */
    protected function getMarkInformation() {
        $markInfo = array();
        $service = $this->getMediaService();
        $marks = $service->getMarkNames();
        foreach ( $marks as $key => $name ) {
            $markInfo[$key]['name']     = $name;
            $markInfo[$key]['count']    = (0 === $key) ? $service->countUnmarked() : $service->countMarked($key);
            $markInfo[$key]['isActive'] = $this->currentMark === $key;
        }
        return $markInfo;
    }
    
    /**
     * 
     */
    abstract protected function getMarkActions();
}