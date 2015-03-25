<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * The action class for movie/ignore action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Rate extends Basic { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id, $score ) {
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        if ( !$movieService->has($id) ) {
            echo array('error'=>'movie does not exists.');
            return;
        }
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $maxRateScore = intval($moduleConfig->get('movie_rate_max_score'));
        $score = intval($score);
        if ( 0 > $score ) {
            $score = 0;
        }
        if ( $score > $maxRateScore ) {
            $score = $maxRateScore;
        }
        
        $movieAccount = $movieService->getCurrentAccount();
        $movieAccount->setScore($id, $score);
    }
}