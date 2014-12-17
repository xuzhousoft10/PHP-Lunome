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
class ClassicDialogues extends Basic { 
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
        $dialogues = $movieService->getClassicDialogues($id, ($page-1)*$pageSize, $pageSize);
        foreach ( $dialogues as $index => $dialogue ) {
            $dialogues[$index] = $dialogue->toArray();
        }
        echo json_encode($dialogues);
    }
}