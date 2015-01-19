<?php
/**
 * 
 */
namespace X\Module\Lunome\Action\Movie\Interaction;

/**
 * 
 */
use X\Module\Lunome\Util\Action\Userinteraction;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * 
 */
class FindTopic extends Userinteraction {
    /**
     * @var unknown
     */
    private $movieService = null;
    
    /**
     * @param unknown $id
     */
    public function runAction( $id ) {
        $this->setActiveInteractionMenuItem(self::INTERACTION_MENU_ITEM_GET_TOPIC);
        $this->interactionMenuParams = array('id'=>$id);
        $friendInformation = $this->userService->getAccount()->getInformation($id);
        
        $movies = array();
        
        $this->movieService = $this->getMovieService();
        $myAccount = $this->userService->getCurrentUserId();
        $accounts = array($myAccount, $id);
        $movies['like'] = $this->movieService->getWatchedMoviesByAccounts($accounts, 5, MovieService::SCORE_OPERATOR_GREATER, 10);
        $movies['dislike'] = $this->movieService->getWatchedMoviesByAccounts($accounts, 5, MovieService::SCORE_OPERATOR_LESS_OR_EQUAL, 10);
        
        /* 填充封面信息和评分信息 */
        $movies['like'] = $this->fillMovieCovers($movies['like']);
        $movies['dislike'] = $this->fillMovieCovers($movies['dislike']);
        
        $name   = 'MOVIE_INTERACTION_FIND_TOPIC';
        $path   = $this->getParticleViewPath('Movie/Interaction/FindTopic');
        $option = array();
        $data   = array('movies'=>$movies, 'friendInformation'=>$friendInformation);
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->title = '想与TA聊聊电影';
    }
    
    private function fillMovieCovers( $movies ) {
        foreach ( $movies as $index => $movie ) {
            $movies[$index] = $movie->toArray();
            if ( 0 === $movie->has_cover*1 ) {
                $movies[$index]['cover'] = $this->movieService->getMediaDefaultCoverURL();
            } else {
                $movies[$index]['cover'] = $this->movieService->getCoverURL($movie->id);
            }
        }
        return $movies;
    }
}