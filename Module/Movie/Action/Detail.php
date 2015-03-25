<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
/**
 * The action class for movie/detail action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Detail extends Visual {
    /**
     * @param string $id The id of the movie.
     */
    public function runAction( $id ) {
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        if ( !$movieService->has($id) ) {
            $this->throw404();
        }
        
        $currentAccount = $this->getCurrentAccount();
        $isGuest = (null === $currentAccount);
        
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('BlankThin'));
        $viewName   = 'MEDIA_DETAIL';
        $path       = $this->getParticleViewPath('Detail');
        $detailView = $view->getParticleViewManager()->load($viewName, $path);
        
        /* add movie data to view. */
        $movie = $movieService->get($id);
        $detailView->getDataManager()->set('movie', $movie);
        $detailView->getDataManager()->set('markCount', $this->getMovieMarkCounts($movieService, $movie));
        
        $movieAccount = $movieService->getCurrentAccount();
        /* add my mark of this movie to view. */
        $myMark = $isGuest ? Movie::MARK_UNMARKED : $movieAccount->getMark($movie->get('id'));
        $detailView->getDataManager()->set('myMark', $myMark);
        
        /* add mark styles. */
        $styles = array(
            Movie::MARK_UNMARKED      => 'warning',
            Movie::MARK_INTERESTED    => 'success',
            Movie::MARK_WATCHED       => 'info',
            Movie::MARK_IGNORED       => 'default'
        );
        $detailView->getDataManager()->set('markStyles', $styles);
        
        /* add share message to view. */
        $message = '';
        /* try to use share message from category, or use the default share message. */
        $categories = $movie->getCategories();
        if ( 0 < count($categories) ) {
            $index = rand(0, count($categories)-1);
            /* @var $category \X\Module\Lunome\Model\Movie\MovieCategoryModel */
            $category = $categories[$index];
            switch ( $myMark ) {
            case Movie::MARK_INTERESTED  : $message=$category->get('beg_message');break;
            case Movie::MARK_WATCHED     : $message=$category->get('recommend_message');break;
            default                             : $message=$category->get('share_message');break;
            }
        }
        if ( empty($message) ) {
            switch ( $myMark ) {
            case Movie::MARK_INTERESTED :$message='怀着各种复杂与激动的心情， 我来到了这里， 我抬头， 望了望天，想起了你，此时此刻， 我的心情不是别人所能理解的，土豪，请我看场《'.$movie->get('name').'》呗？';break;
            case Movie::MARK_WATCHED    :$message='看完《'.$movie->get('name').'》， 我和我的小伙伴们都惊呆了！ GO！ GO! GO! ';break;
            default:$message='';break;
            }
        }
        $detailView->getDataManager()->set('shareMessage', $message);
        
        /* add share message title to view. */
        $shareMessageTitle = '分享';
        if ( Movie::MARK_INTERESTED === $myMark ) {
            $shareMessageTitle = '求包养';
        } else if ( Movie::MARK_WATCHED === $myMark ) {
            $shareMessageTitle = '推荐给好友';
        } else {
            $shareMessageTitle = '分享';
        }
        $detailView->getDataManager()->set('shareMessageTitle', $shareMessageTitle);
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $markNames = $moduleConfig->get('movie_mark_names');
        /* add other data to view. */
        $detailView->getDataManager()->set('markNames', $markNames);
        $detailView->getDataManager()->set('isGuestUser', $isGuest);
        $detailView->getDataManager()->set('currentUser', $currentAccount);
        
        $view->title = $movie->get('name');
    }
    
    /**
     * @param MovieService $movieService
     * @param Movie $movie
     */
    private function getMovieMarkCounts( $movieService, $movie ) {
        $movieAccount = $movieService->getCurrentAccount();
        $moduleConfig = $this->getModule()->getConfiguration();
        /* add mark count info to view. */
        $markCount = array();
        $markNames = $moduleConfig->get('movie_mark_names');
        unset($markNames[0]);
        
        foreach ( $markNames as $mark => $name ) {
            $markCount[$mark]['all']  = $movie->countMarked($mark);
            $markCount[$mark]['friend'] = $movieAccount->countMarkedFriends($movie->get('id'), $mark);
        }
        return $markCount;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $assetsURL = $this->getAssetsURL();
        $scriptManager = $this->getView()->getScriptManager();
        $scriptManager->addFile('ajaxfileupload', $assetsURL.'/library/jquery/plugin/ajaxfileupload.js');
        $scriptManager->addFile('detail-detail', $assetsURL.'/js/movie/detail.js');
    }
}