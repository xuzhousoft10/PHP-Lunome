<?php
namespace X\Module\Movie\Action\Home;
/**
 * 
 */
use X\Module\Lunome\Util\Action\VisualUserHome;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
use X\Module\Lunome\Widget\Pager\Simple as SimplePager;
/**
 * 
 */
class Index extends VisualUserHome {
    /**
     * @var integer
     */
    private $currentMark = null;
    
    /**
     * @param unknown $id
     * @param string $mark
     * @param number $page
     */
    public function runAction( $id, $mark=Movie::MARK_INTERESTED, $page=1 ) {
        /* @var $accountService AccountService */
        $accountService = $this->getService(AccountService::getServiceName());
        if ( !$accountService->exists($id) ) {
            return $this->throw404();
        }
        
        $mark = (int)$mark;
        $this->currentMark = $mark;
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $marks = $moduleConfig->get('movie_mark_names');
        unset($marks[Movie::MARK_UNMARKED]);
        $marks = array('data'=>$marks, 'actived'=>$mark);
        
        $pageSize = $moduleConfig->get('movie_user_home_page_size');
        $page = intval($page);
        $page = ( 0 >= $page ) ? 1 : $page;
        $position = ($page-1)*$pageSize;
        
        $criteria = new Criteria();
        $criteria->limit = $pageSize;
        $criteria->position = $position;
        $movieService = $this->getService(MovieService::getServiceName());
        /* @var $movieService MovieService */
        $movieAccount = $movieService->getAccount($id);
        $movies = $movieAccount->findMarked($mark, $criteria);
        
        $pager = new SimplePager();
        $pager->setPageSize($pageSize);
        $pager->setCurrentPage($page);
        $pager->setTotalNumber($movieAccount->countMarked($mark));
        $pager->setPagerURL($this->createURL('/?module=movie&action=home/index', array('id'=>$id, 'mark'=>$mark, 'page'=>'{$page}')));
        $pager->enablePageInformation();
        
        /* User home index */
        $name   = 'MOVIE_HOME_INDEX';
        $path   = $this->getParticleViewPath('HomeIndex');
        $data   = array(
            'accountID'     => $id, 
            'marks'         => $marks, 
            'movies'        => $movies, 
            'pager'         => $pager,
            'movieAccount'  => $movieAccount,
            'currentMark'   => $this->currentMark,
        );
        $this->loadParticle($name, $path)->getDataManager()->merge($data);
        $this->homeUserAccountID = $id;
    }
}