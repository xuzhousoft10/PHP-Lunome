<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie\Character;

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
        
        $pageSize = 2;
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $characters = $movieService->getCharacters($id, ($page-1)*$pageSize, $pageSize);
        foreach ( $characters as $index => $character ) {
            $characters[$index] = $character->toArray();
            $characters[$index]['imageURL'] = $movieService->getCharacterUrlById($character->id);
        }
        echo json_encode($characters);
    }
}