<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Comment extends Basic { 
    /**
     * @param unknown $id
     * @param unknown $content
     */
    public function runAction( $id, $content ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        if ( !empty($content) ) {
            $movieService->addShortComment($id, $content);
        }
    }
}