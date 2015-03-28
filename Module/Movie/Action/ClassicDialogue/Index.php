<?php
namespace X\Module\Movie\Action\ClassicDialogue;
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
     * @param string $id
     * @param integer $page
     */
    public function runAction( $id, $page=1 ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($id);
        if ( null === $movie ) {
            $this->throw404();
        }
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_classic_dialogue_page_size');
        $page = (0 >= (int)$page) ? 1 : (int)$page;
        $criteria = new Criteria();
        $criteria->limit = $pageSize;
        $criteria->position = ($page-1)*$pageSize;
        $dialogueManager = $movie->getClassicDialogueManager();
        $dialogues = $dialogueManager->find($criteria);
        
        $pager = new SimplePager();
        $pager->setPagerURL($this->createURL('/',array('module'=>'movie','action'=>'classicDialogue/index','id'=>$id,'page'=>'{$page}')));
        $pager->setCurrentPage($page);
        $pager->setPageSize($pageSize);
        $pager->setTotalNumber($dialogueManager->count());
        
        $movieAccount = $movieService->getCurrentAccount();
        $name   = 'CLASSIC_DIALOGUES_INDEX';
        $path   = $this->getParticleViewPath('ClassicDialogues');
        $data   = array('dialogues'=>$dialogues, 'id'=>$id, 'pager'=>$pager, 'isWatched'=>$movieAccount->isWatched($id));
        $view = $this->getView()->getParticleViewManager()->load($name, $path);
        $view->getDataManager()->merge($data);
        $view->display();
    }
}