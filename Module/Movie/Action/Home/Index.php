<?php
namespace X\Module\Movie\Action\Home;
/**
 * use statements
 */
use X\Module\Lunome\Util\Action\VisualUserHome;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Account\Service\Account\Service as AccountService;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
/**
 * Index
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Index extends VisualUserHome {
    /**
     * @var unknown
     */
    private $currentMark = null;
    
    /**
     * @param string $id
     * @param integer $mark
     * @param integer $page
     */
    public function runAction( $id, $mark=null, $page=1 ) {
        $accountService = $this->getService(AccountService::getServiceName());
        /* @var $accountService AccountService */
        $account = $accountService->getCurrentAccount();
        if ( null === $account ) {
            $this->throw404();
        }
        
        $mark = intval($mark);
        $this->currentMark = $mark;
        if ( Movie::MARK_UNMARKED === $mark ) {
            $mark = Movie::MARK_INTERESTED;
        }
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $pageSize = $moduleConfig->get('movie_user_home_page_size');
        $marks = $moduleConfig->get('movie_mark_names');
        unset($marks[Movie::MARK_UNMARKED]);
        $marks = array('data'=>$marks, 'actived'=>$mark);
        
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
        
        /* setup pager */
        $pager = array('prev'=>false, 'next'=>false);
        $count = $movieAccount->countMarked($mark);
        $pager['total'] = (0===$count%$pageSize) ? ($count/$pageSize) : (intval($count/$pageSize)+1);
        $pager['current'] = $page;
        $pager['prev'] = (0 >= $page-1) ? false : $page-1;
        $pager['next'] = ($pager['total']<$page+1) ? false : $page+1;
        
        /* User home index */
        $name   = 'MOVIE_HOME_INDEX';
        $path   = $this->getParticleViewPath('HomeIndex');
        $option = array();
        $data   = array(
            'accountID' => $id, 
            'marks'     => $marks, 
            'movies'    => $movies, 
            'pager'     => $pager
        );
        $this->loadParticle($name, $path, $option, $data);
        
        $this->homeUserAccountID = $id;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $assetsURL = $this->getAssetsURL();
        $this->addScriptFile('movie-index', $assetsURL.'/js/movie/home.js');
        
        if (Movie::MARK_WATCHED === $this->currentMark) {
            $this->addScriptFile('rate-it', $assetsURL.'/library/jquery/plugin/rate/rateit.js');
            $this->addCssLink('rate-it', $assetsURL.'/library/jquery/plugin/rate/rateit.css');
        }
    }
}