<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie\Character;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Index extends Visual { 
    /**
     * @param unknown $id
     * @param unknown $content
     */
    public function runAction( $id, $page=1 ) {
        $page *= 1;
        if ( 0 >= $page ) {
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
        
        $pager = array();
        $pager['prev'] = (1 >= $page) ? false : $page-1;
        $pager['next'] = (($page)*$pageSize >= $movieService->countCharacters($id)) ? false : $page+1;
        
        $isWatched = MovieService::MARK_WATCHED === $movieService->getMark($id);
        $name   = 'CHARACTER_INDEX';
        $path   = $this->getParticleViewPath('Movie/Characters');
        $option = array();
        $data   = array('characters'=>$characters, 'id'=>$id, 'pager'=>$pager, 'isWatched'=>$isWatched);
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->displayParticle($name);
    }
}