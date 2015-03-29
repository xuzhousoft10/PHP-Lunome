<?php
namespace X\Module\Movie\Action\Poster;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Lunome\Widget\Pager\Simple as SimplePager;
/**
 * 
 */
class Index extends Visual { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id, $page=1 ) {
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_poster_page_size');
        $page = (0>(int)$page) ? 1 : (int)$page;
        
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($id);
        if ( null === $movie ) {
            return $this->throw404();
        }
        
        $criteria = new Criteria();
        $criteria->limit = $pageSize;
        $criteria->position = ($page-1)*$pageSize;
        $posterManager = $movie->getPosterManager();
        $posters = $posterManager->find($criteria);
        
        $pager = new SimplePager();
        $pager->setCurrentPage($page);
        $pager->setPagerURL($this->createURL('/?module=movie&action=poster/index', array('id'=>$id, 'page'=>'{$page}')));
        $pager->setTotalNumber($posterManager->count());
        $pager->setPageSize($pageSize);
        
        $movieAccount = $movieService->getCurrentAccount();
        $name   = 'POSTERS_INDEX';
        $path   = $this->getParticleViewPath('Posters');
        $option = array();
        $data   = array(
            'posters'   => $posters, 
            'id'        => $id, 
            'pager'     => $pager, 
            'isWatched' => $movieAccount->isWatched($id),
        );
        
        $view = $this->loadParticle($name, $path);
        $view->getDataManager()->merge($data);
        $view->display();
    }
}