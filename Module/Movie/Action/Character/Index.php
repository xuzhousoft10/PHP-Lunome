<?php
namespace X\Module\Movie\Action\Character;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
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
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_character_page_size');
        $page = (0>= (int)$page) ? 1 : (int)$page;
        $criteria = new Criteria();
        $criteria->limit = $pageSize;
        $criteria->position = ($page-1)*$pageSize;
        
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($id);
        if ( null === $movie ) {
            $this->throw404();
        }
        $characterManager = $movie->getCharacterManager();
        $characters = $characterManager->find($criteria);
        $pager = array();
        $pager['prev'] = (1 >= $page) ? false : $page-1;
        $pager['next'] = (($page)*$pageSize >= $characterManager->count()) ? false : $page+1;
        
        $movieAccount = $movieService->getCurrentAccount();
        $isWatched = Movie::MARK_WATCHED === $movieAccount->getMark($id);
        $name   = 'CHARACTER_INDEX';
        $path   = $this->getParticleViewPath('Characters');
        $data   = array('characters'=>$characters, 'id'=>$id, 'pager'=>$pager, 'isWatched'=>$isWatched);
        $view = $this->getView()->getParticleViewManager()->load($name, $path);
        $view->getDataManager()->merge($data);
        $view->display();
    }
}