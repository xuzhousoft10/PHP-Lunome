<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Character;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/character/index action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Index extends Visual { 
    /**
     * @param string $id
     * @param integer $page
     */
    public function runAction( $id, $page=1 ) {
        $movieService = $this->getMovieService();
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_character_page_size');
        
        $page = intval($page);
        if ( 0 >= $page ) {
            $page = 1;
        }
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
        $data   = array('characters'=>$characters, 'id'=>$id, 'pager'=>$pager, 'isWatched'=>$isWatched);
        $view = $this->getView()->getParticleViewManager()->load($name, $path);
        $view->getDataManager()->merge($data);
        $view->display();
    }
}