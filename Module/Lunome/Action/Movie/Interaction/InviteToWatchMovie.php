<?php
/**
 * 
 */
namespace X\Module\Lunome\Action\Movie\Interaction;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Userinteraction;

/**
 * 
 */
class InviteToWatchMovie extends Userinteraction {
    /**
     * @param unknown $id
     */
    public function runAction( $id ) {
        $this->setActiveInteractionMenuItem(self::INTERACTION_MENU_ITEM_INVITE_TO_WATCH_MOVIE);
        $this->interactionMenuParams = array('id'=>$id);
        $friendInformation = $this->userService->getAccount()->getInformation($id);
        
        $movieService = $this->getMovieService();
        $myAccount = $this->userService->getCurrentUserId();
        $accounts = array($myAccount, $id);
        $movies = $movieService->getInterestedMovieSetByAccounts($accounts, 10);
        
        /* 填充封面信息和评分信息 */
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = $movie->toArray();
            if ( 0 === $movie->has_cover*1 ) {
                $movies[$index]['cover'] = $movieService->getMediaDefaultCoverURL();
            } else {
                $movies[$index]['cover'] = $movieService->getCoverURL($movie->id);
            }
        }
        
        $name   = 'MOVIE_INTERACTION_INVITETOWATCHMOVIE';
        $path   = $this->getParticleViewPath('Movie/Interaction/InviteToWatchMovie');
        $option = array();
        $data   = array('movies'=>$movies, 'friendInformation'=>$friendInformation);
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->title = '想请TA看场电影';
    }
}