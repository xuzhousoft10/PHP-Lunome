<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/detail action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Detail extends Visual {
    /**
     * @param string $id The id of the movie.
     */
    public function runAction( $id ) {
        /* setup env values. */
        $movieService = $this->getMovieService();
        $view = $this->getView();
        $isGuest = $this->getUserService()->getIsGuest();
        $moduleConfig = $this->getModule()->getConfiguration();
        
        /* check parameters. */
        if ( !$movieService->has($id) ) {
            $this->throw404();
            return;
        }
        
        /* setup view. */
        $view->loadLayout($this->getLayoutViewPath('BlankThin'));
        $viewName   = 'MEDIA_DETAIL';
        $path       = $this->getParticleViewPath('Movie/Detail');
        $view->loadParticle($viewName, $path);
        
        /* add movie data to view. */
        $movie = $movieService->get($id);
        if ( 0 === $movie['has_cover']*1 ) {
            $movie['cover'] = $movieService->getMediaDefaultCoverURL();
        } else {
            $movie['cover'] = $movieService->getCoverURL($movie['id']);
        }
        $movie['region']    = $movieService->getRegionById($movie['region_id']);
        $movie['language']  = $movieService->getLanguageById($movie['language_id']);
        $movie['category']  = $movieService->getCategoriesByMovieId($movie['id']);
        $movie['directors'] = $movieService->getDirectors($movie['id']);
        $movie['actors']    = $movieService->getActors($movie['id']);
        $view->setDataToParticle($viewName, 'movie', $movie);
                
        /* add mark count info to view. */
        $markCount = array();
        $class = new \ReflectionClass($movieService);
        $consts = $class->getConstants();
        $marks = array();
        foreach ( $consts as $name => $value ) {
            if ( 'MARK_' === substr($name, 0, 5) && MovieService::MARK_UNMARKED !== $value ) {
                $marks[] = $value;
            }
        }
        foreach ( $marks as $markValue ) {
            $markCount[$markValue]['all']  = $movieService->countMarkedUsers($id, $markValue);
            $markCount[$markValue]['friend'] = $movieService->countMarkedFriends($id, $markValue);
        }
        $view->setDataToParticle($viewName, 'markCount', $markCount);
        
        /* add my mark of this movie to view. */
        $myMark = $isGuest ? MovieService::MARK_UNMARKED : $movieService->getMark($id);
        $view->setDataToParticle($viewName, 'myMark', $myMark);
        
        /* add mark styles. */
        $styles = array(
            MovieService::MARK_UNMARKED      => 'warning',
            MovieService::MARK_INTERESTED    => 'success',
            MovieService::MARK_WATCHED       => 'info',
            MovieService::MARK_IGNORED       => 'default'
        );
        $view->setDataToParticle($viewName, 'markStyles', $styles);
        
        /* add share message to view. */
        $message = '';
        /* try to use share message from category, or use the default share message. */
        $categories = $movieService->getCategoriesByMovieId($id);
        if ( 0 < count($categories) ) {
            $index = rand(0, count($categories)-1);
            /* @var $category \X\Module\Lunome\Model\Movie\MovieCategoryModel */
            $category = $categories[$index];
            switch ( $myMark ) {
            case MovieService::MARK_INTERESTED  : $message=$category->beg_message;break;
            case MovieService::MARK_WATCHED     : $message=$category->recommend_message;break;
            default                             : $message=$category->share_message;break;
            }
        }
        if ( empty($message) ) {
            switch ( $myMark ) {
            case MovieService::MARK_INTERESTED :$message='怀着各种复杂与激动的心情， 我来到了这里， 我抬头， 望了望天，想起了你，此时此刻， 我的心情不是别人所能理解的，土豪，请我看场《'.$movie['name'].'》呗？';break;
            case MovieService::MARK_WATCHED    :$message='看完《'.$movie['name'].'》， 我和我的小伙伴们都惊呆了！ GO！ GO! GO! ';break;
            default:$message='';break;
            }
        }
        $view->setDataToParticle($viewName, 'shareMessage', $message);
        
        /* add share message title to view. */
        $shareMessageTitle = '分享';
        if ( MovieService::MARK_INTERESTED === $myMark ) {
            $shareMessageTitle = '求包养';
        } else if ( MovieService::MARK_WATCHED === $myMark ) {
            $shareMessageTitle = '推荐给好友';
        } else {
            $shareMessageTitle = '分享';
        }
        $view->setDataToParticle($viewName, 'shareMessageTitle', $shareMessageTitle);
        
        /* add other data to view. */
        $view->setDataToParticle($viewName, 'markNames', $movieService->getMarkNames());
        $view->setDataToParticle($viewName, 'isGuestUser', $isGuest);
        $userData = $isGuest ? null : $this->getUserService()->getAccount()->getInformation($this->getUserService()->getCurrentUserId());
        $view->setDataToParticle($viewName, 'currentUser', $userData);
        
        $view->title = $movie['name'];
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $assetsURL = $this->getAssetsURL();
        $this->getView()->addScriptFile('ajaxfileupload', $assetsURL.'/library/jquery/plugin/ajaxfileupload.js');
        $this->getView()->addScriptFile('detail-detail', $assetsURL.'/js/movie/detail.js');
    }
}