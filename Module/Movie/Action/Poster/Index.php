<?php
namespace X\Module\Movie\Action\Poster;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
/**
 * The action class for movie/poster/index action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Index extends Visual { 
    /**
     * @param string $id
     * @param integer $page
     */
    public function runAction( $id, $page=1 ) {
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_poster_page_size');
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $movieAccount = $movieService->getCurrentAccount();
        $posterManager = $movieService->get($id)->getPosterManager();
        
        $page = intval($page);
        if ( 0 >= $page ) {
            $page = 1;
        }
        
        $criteria = new Criteria();
        $criteria->limit = $pageSize;
        $criteria->position = ($page-1)*$pageSize;
        $posters = $posterManager->find($criteria);
        
        $pager = array();
        $pager['prev'] = (1 >= $page) ? false : $page-1;
        $pager['next'] = (($page)*$pageSize >= $posterManager->count()) ? false : $page+1;
        
        $isWatched = Movie::MARK_WATCHED === $movieAccount->getMark($id);
        $name   = 'POSTERS_INDEX';
        $path   = $this->getParticleViewPath('Posters');
        $option = array();
        $data   = array(
            'posters'=>$posters, 
            'id'=>$id, 
            'pager'=>$pager, 
            'isWatched'=>$isWatched,
        );
        
        $view = $this->loadParticle($name, $path, $option, $data);
        $view->display();
    }
}