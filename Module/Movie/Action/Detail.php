<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
use X\Module\Lunome\Module as LunomeModule;
/**
 * 
 */
class Detail extends Visual {
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($id);
        if ( null === $movie ) {
            return $this->throw404();
        }
        
        $view = $this->getView();
        $view->setLayout($this->getLayoutViewPath('TwoColumnsBigLeft', LunomeModule::getModuleName()));
        $viewName   = 'MEDIA_DETAIL';
        $path       = $this->getParticleViewPath('Detail');
        $detailView = $view->getParticleViewManager()->load($viewName, $path);
        
        $detailView->getDataManager()->set('movie', $movie);
        
        $detailView->getDataManager()->set('markCount', $this->getMovieMarkCounts($movieService, $movie));
        
        $currentAccount = $this->getCurrentAccount();
        $detailView->getDataManager()->set('currentUser', $currentAccount);
        
        $isGuest = (null === $currentAccount);
        $detailView->getDataManager()->set('isGuestUser', $isGuest);
        
        $movieAccount = $movieService->getCurrentAccount();
        $myMark = $isGuest ? Movie::MARK_UNMARKED : $movieAccount->getMark($movie->get('id'));
        $detailView->getDataManager()->set('myMark', $myMark);
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $detailView->getDataManager()->set('markStyles', $moduleConfig->get('movie_mark_styles'));
        
        $detailView->getDataManager()->set('shareMessage', $this->getShareMessage($movieService, $movie));
        
        switch ( $myMark ) {
        case Movie::MARK_INTERESTED:
            $detailView->getDataManager()->set('shareMessageTitle', '求包养');
            break;
        case Movie::MARK_WATCHED:
            $detailView->getDataManager()->set('shareMessageTitle', '推荐给好友');
            break;
        default:
            $detailView->getDataManager()->set('shareMessageTitle', '分享');
            break;
        }
        
        $markNames = $moduleConfig->get('movie_mark_names');
        $detailView->getDataManager()->set('markNames', $markNames);
        
        $toolBar = $this->getParticleViewPath('DetailToolBar');
        $toolBar = $view->getParticleViewManager()->load('DETAIL_TOOL_BAR', $toolBar);
        $toolBar->getOptionManager()->set('zone', 'right');
        $toolBar->getDataManager()->set('movie', $movie);
        $toolBar->getDataManager()->set('movieAccount', $movieAccount);
        
        $isWatched = $movieAccount->isWatched($movie->get('id'));
        if ( $isWatched ) {
            $ratePanel = $this->getParticleViewPath('DetailRatePanel');
            $ratePanel = $view->getParticleViewManager()->load('DETAIL_RATE_PANEL', $ratePanel);
            $ratePanel->getOptionManager()->set('zone', 'right');
            $ratePanel->getDataManager()->set('movie', $movie);
            $ratePanel->getDataManager()->set('movieAccount', $movieAccount);
        }
        
        $sharePanel = $this->getParticleViewPath('DetailSharePanel');
        $sharePanel = $view->getParticleViewManager()->load('DETAIL_SHARE_PANEL', $sharePanel);
        $sharePanel->getOptionManager()->set('zone', 'right');
        $sharePanel->getDataManager()->set('movie', $movie);
        $sharePanel->getDataManager()->set('movieAccount', $movieAccount);
        
        $suggestionBar = $this->getParticleViewPath('DetailSuggestionBar');
        $suggestionBar = $view->getParticleViewManager()->load('DETAIL_SUGGESTION_BAR', $suggestionBar);
        $suggestionBar->getOptionManager()->set('zone', 'right');
        $suggestionBar->getDataManager()->set('markStyles', $moduleConfig->get('movie_mark_styles'));
        $suggestionBar->getDataManager()->set('markNames', $markNames);
        $suggestionBar->getDataManager()->set('movie', $movie);
        
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
     * @param Movie $movie
     * @return string
     */
    private function getShareMessage( MovieService $service, Movie $movie ) {
        $movieAccount = $service->getCurrentAccount();
        $myMark = $movieAccount->getMark($movie->get('id'));
        $moduleConfig = $this->getModule()->getConfiguration();
        
        $message = '';
        $categories = $movie->getCategories();
        if ( 0 < count($categories) ) {
            $index = rand(0, count($categories)-1);
            /* @var $category \X\Module\Lunome\Model\Movie\MovieCategoryModel */
            $category = $categories[$index];
            switch ( $myMark ) {
            case Movie::MARK_INTERESTED  : $message=$category->get('beg_message');break;
            case Movie::MARK_WATCHED     : $message=$category->get('recommend_message');break;
            default                      : $message=$category->get('share_message');break;
            }
        }
        if ( empty($message) ) {
            switch ( $myMark ) {
            case Movie::MARK_INTERESTED :$message=$moduleConfig->get('movie_beg_message'); break;
            case Movie::MARK_WATCHED    :$message=$moduleConfig->get('movie_recommend_message'); break;
            default:$message='';break;
            }
            $message = str_replace('{$name}', $movie->get('name'), $message);
        }
        
        return $message;
    }
}