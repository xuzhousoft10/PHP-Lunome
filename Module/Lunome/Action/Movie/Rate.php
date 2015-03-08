<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;
/**
 * The action class for movie/ignore action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Rate extends Basic { 
    /**
     * @param string $id
     * @param integer $score
     */
    public function runAction( $id, $score ) {
        $movieService = $this->getMovieService();
        $moduleConfig = $this->getModule()->getConfiguration();
        $maxRateScore = intval($moduleConfig->get('movie_rate_max_score'));
        
        if ( !$movieService->has($id) ) {
            echo array('error'=>'movie does not exists.');
            return;
        }
        
        $score = intval($score);
        if ( 0 > $score ) {
            $score = 0;
        }
        if ( $score > $maxRateScore ) {
            $score = $maxRateScore;
        }
        
        $movieService->setRateScore($id, $score);
    }
}