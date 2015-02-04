<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Interaction;

/**
 * use statement
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Userinteraction;
use X\Module\Lunome\Model\Movie\MovieInvitationModel;

/**
 * InviteToWatchMovie
 * @author Michael Luthor <michaelluthor@163.com>
 */
class InviteToWatchMovie extends Userinteraction {
    /**
     * @param string $id
     */
    public function runAction( $id ) {
        $friendInformation = $this->userService->getAccount()->getInformation($id);
        $movieService = $this->getMovieService();
        $myAccount = $this->userService->getCurrentUserId();
        $accounts = array($myAccount, $id);
        $movies = $movieService->getInterestedMovieSetByAccounts($accounts, 10);
        $view = $this->getView();
        
        /* 填充封面信息和评分信息 */
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = $movie->toArray();
            if ( 0 === $movie->has_cover*1 ) {
                $movies[$index]['cover'] = $movieService->getMediaDefaultCoverURL();
            } else {
                $movies[$index]['cover'] = $movieService->getCoverURL($movie->id);
            }
        }
        
        $viewName = 'MOVIE_INTERACTION_INVITETOWATCHMOVIE';
        $path   = $this->getParticleViewPath('Movie/Interaction/InviteToWatchMovie');
        $view->loadParticle($viewName, $path);
        $view->setDataToParticle($viewName, 'movies', $movies);
        $view->setDataToParticle($viewName, 'friendInformation', $friendInformation);
        $commentLength = MovieInvitationModel::model()->getAttribute('comment')->getLength();
        $view->setDataToParticle($viewName, 'commentLength', $commentLength);
        
        $view->title = '想请TA看场电影';
        $this->setActiveInteractionMenuItem(self::INTERACTION_MENU_ITEM_INVITE_TO_WATCH_MOVIE);
        $this->interactionMenuParams = array('id'=>$id);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        $assetsURL = $this->getAssetsURL();
        $this->getView()->addScriptFile('invite', $assetsURL.'/js/movie/invite.js');
    }
}