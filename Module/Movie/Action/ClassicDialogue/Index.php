<?php
namespace X\Module\Movie\Action\ClassicDialogue;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
/**
 * The action class for movie/classicDialogue/index action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Index extends Visual { 
    /**
     * @param string $id
     * @param integer $page
     */
    public function runAction( $id, $page=1 ) {
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_detail_classic_dialogue_page_size');
        
        $page = intval($page);
        if ( 0 >= $page ) {
            $page = 1;
        }
        
        $movie = $movieService->get($id);
        $dialogueManager = $movie->getClassicDialogueManager();
        
        $criteria = new Criteria();
        $criteria->limit = $pageSize;
        $criteria->position = ($page-1)*$pageSize;
        $dialogues = $dialogueManager->find($criteria);
        
        $pager = array();
        $pager['prev'] = (1 >= $page) ? false : $page-1;
        $pager['next'] = (($page)*$pageSize >= $dialogueManager->count()) ? false : $page+1;
        
        $isWatched = Movie::MARK_WATCHED === $movieService->getCurrentAccount()->getMark($id);
        $name   = 'CLASSIC_DIALOGUES_INDEX';
        $path   = $this->getParticleViewPath('ClassicDialogues');
        $data   = array('dialogues'=>$dialogues, 'id'=>$id, 'pager'=>$pager, 'isWatched'=>$isWatched);
        $view = $this->getView()->getParticleViewManager()->load($name, $path);
        $view->getDataManager()->merge($data);
        $view->display();
    }
}