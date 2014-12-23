<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie\Poster;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Index extends Basic { 
    /**
     * @param unknown $id
     * @param unknown $content
     */
    public function runAction( $id, $page=1 ) {
        if ( 0 >= $page*1 ) {
            $page = 1;
        }
        
        $pageSize = 10;
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $posters = $movieService->getPosters($id, ($page-1)*$pageSize, $pageSize);
        foreach ( $posters as $index => $poster ) {
            $posters[$index] = $poster->toArray();
            $posters[$index]['url'] = $movieService->getPosterUrlById($poster->id);
        }
        echo json_encode($posters);
    }
}