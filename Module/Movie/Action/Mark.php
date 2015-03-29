<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
use X\Service\QQ\Service as QQService;
use X\Module\Lunome\Util\Action\JSON;
/**
 * 
 */
class Mark extends JSON { 
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Mark::runAction()
     */
    public function runAction( $id, $mark, $redirect=false ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($id);
        
        /* check movie exists. */
        if ( null === $movie ) {
            if ( $redirect ) {
                return $this->throw404();
            } else {
                return $this->error('Movie does not exists.');
            }
        }
        
        $movieService->getCurrentAccount()->mark($id, $mark);
        $this->sendMarkShareMessageToSNS($movieService, $movie, $mark);
        
        /* redirect if needed. */
        if ( $redirect ) {
            $this->goBack();
        }
    }
    
    /**
     * @param MovieService $movieService
     * @param Movie $movie
     * @param integer $mark
     */
    private function sendMarkShareMessageToSNS( MovieService $movieService, Movie $movie, $mark ) {
        /* public mark action to sns if needed. */
        $currentAccount = $this->getCurrentAccount();
        $isAutoShare = $currentAccount->getConfigurationManager()->get('sns', 'auto_share', '1');
        $isAutoShare = '1' === $isAutoShare;
        if ( !$isAutoShare ) {
            return;
        }
        
        $moduleConfig = $this->getModule()->getConfiguration();
        
        $message = '';
        switch ( $mark ) {
        case Movie::MARK_INTERESTED  : $message = $moduleConfig->get('movie_mark_interested_sns_message'); break;
        case Movie::MARK_WATCHED     : $message = $moduleConfig->get('movie_mark_watched_sns_message');break;
        case Movie::MARK_IGNORED     : $message = $moduleConfig->get('movie_mark_ignored_sns_message');break;
        default:$message='';break;
        }
        $message = str_replace('{$name}', $movie->get('name'), $message);
        $message .= "\n";
        $message .= 'http://'.$_SERVER['HTTP_HOST'].'/?module=movie&action=detail&id='.$movie->get('id');
        
        $image = tempnam(sys_get_temp_dir(), 'LMK');
        file_put_contents($image, file_get_contents($movie->getCoverURL()));
        
        /* send message to sns. */
        $oauth = $currentAccount->getOAuthInformation();
        if ( null !== $oauth && 'QQ' === $oauth->get('server_name') ) {
            /* @var $QQService QQService */
            $QQService = $this->getService(QQService::getServiceName());
            $QQConnect = $QQService->getConnect();
            $QQConnect->setOpenId($oauth->get('openid'));
            $QQConnect->setAccessToken($oauth->get('access_token'));
            $QQConnect->Tweet()->addWithPicture($message, $image);
        }
        unlink($image);
    }
}