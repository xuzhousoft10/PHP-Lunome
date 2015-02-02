<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie\Interaction;

/**
 * use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Userinteraction;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * FindTopic
 * @author Michael Luthor <michaelluthor@163.com>
 */
class FindTopic extends Userinteraction {
    /**
     * @var \X\Module\Lunome\Service\Movie\Service
     */
    private $movieService = null;
    
    /**
     * @param string $id
     */
    public function runAction( $id ) {
        $this->movieService = $this->getMovieService();
        $myAccount = $this->userService->getCurrentUserId();
        $friendInformation = $this->userService->getAccount()->getInformation($id);
        $moduleConfig = $this->getModule()->getConfiguration();
        $maxScore = intval($moduleConfig->get('movie_rate_max_score'));
        $suggestedSize = intval($moduleConfig->get('movie_topic_suggested_size'));
        
        $movies = array();
        $accounts = array($myAccount, $id);
        $movies['like'] = $this->movieService->getWatchedMoviesByAccounts($accounts, $maxScore/2, MovieService::SCORE_OPERATOR_GREATER, $suggestedSize);
        $movies['dislike'] = $this->movieService->getWatchedMoviesByAccounts($accounts, $maxScore/2, MovieService::SCORE_OPERATOR_LESS_OR_EQUAL, $suggestedSize);
        
        /* 填充封面信息和评分信息 */
        $movies['like'] = $this->fillMovieCovers($movies['like']);
        $movies['dislike'] = $this->fillMovieCovers($movies['dislike']);
        
        $name   = 'MOVIE_INTERACTION_FIND_TOPIC';
        $path   = $this->getParticleViewPath('Movie/Interaction/FindTopic');
        $option = array();
        $data   = array(
            'movies'=>$movies, 
            'friendInformation'=>$friendInformation,
            'assetsURL'=>X::system()->getConfiguration()->get('assets-base-url'));
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->title = '想与TA聊聊电影';
        
        $this->setActiveInteractionMenuItem(self::INTERACTION_MENU_ITEM_GET_TOPIC);
        $this->interactionMenuParams = array('id'=>$id);
    }
    
    /**
     * @param array $movies
     * @return string
     */
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
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        $assetsURL = X::system()->getConfiguration()->get('assets-base-url');
        $this->getView()->addScriptFile('user-home-movie-index', $assetsURL.'/js/movie/topic.js');
    }
}