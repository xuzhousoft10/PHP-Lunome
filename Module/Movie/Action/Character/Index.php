<?php
namespace X\Module\Movie\Action\Character;
/**
 * 
 */
use X\Core\X;
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
        
        $pager = new SimplePager();
        $pager->setTotalNumber($characterManager->count());
        $pager->setPageSize($pageSize);
        $pager->setCurrentPage($page);
        $pager->setPagerURL($this->createURL('/',array('module'=>'movie', 'action'=>'character/index','id'=>$id, 'page'=>'{$pager}')));
        
        $movieAccount = $movieService->getCurrentAccount();
        $name   = 'CHARACTER_INDEX';
        $path   = $this->getParticleViewPath('Characters');
        $view = $this->getView()->getParticleViewManager()->load($name, $path);
        $view->getDataManager()
            ->set('characters', $characters)
            ->set('id', $id)
            ->set('pager', $pager)
            ->set('isWatched', $movieAccount->isWatched($id));
        $view->display();
    }
}