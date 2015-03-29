<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Module\Lunome\Util\Action\JSON;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * 
 */
class Rate extends JSON { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id, $score ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        if ( !$movieService->has($id) ) {
            return $this->error('Movie does not exists.');
        }
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $maxRateScore = (int)$moduleConfig->get('movie_rate_max_score');
        
        $score = (0>(int)$score) ? 0 : (int)$score;
        $score = ($score>$maxRateScore) ? $maxRateScore : $score;
        
        $movieAccount = $movieService->getCurrentAccount();
        $movieAccount->setScore($id, $score);
        return $this->success();
    }
}