<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
use X\Service\QQ\Service as QQService;
/**
 * The action class for movie/mark action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Mark extends Basic { 
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Media\Mark::runAction()
     */
    public function runAction( $id, $mark, $redirect=false ) {
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        
        /* check movie exists. */
        if ( !$movieService->has($id) ) {
            if ( $redirect ) {
                $this->throw404();
            } else {
                echo json_encode(array('error'=>'movie does not exists.'));
                return ;
            }
        }
        
        $movieService->getCurrentAccount()->mark($id, $mark);
        
        /* public mark action to sns if needed. */
        $currentAccount = $this->getCurrentAccount();
        $isAutoShare = $currentAccount->getConfigurationManager()->get('sns', 'auto_share', '1');
        $isAutoShare = '1' === $isAutoShare;
        if ( $isAutoShare ) {
            $movie = $movieService->get($id);
            $message = '';
            
            switch ( $mark ) {
            case Movie::MARK_INTERESTED  : $message = '感觉《'.$movie->get('name').'》会挺好看的～～～，小伙伴们都去瞅瞅吧。';break;
            case Movie::MARK_WATCHED     : $message = '看完了《'.$movie->get('name').'》，小伙伴们也去感受感受吧。';break;
            case Movie::MARK_IGNORED     : $message = '《'.$movie->get('name').'》貌似不好看的样子～～～';break;
            default:$message='';break;
            }
            
            $message .= "\n";
            $message .= 'http://'.$_SERVER['HTTP_HOST'].'/?module=movie&action=detail&id='.$movie->get('id');
            
            $image = tempnam(sys_get_temp_dir(), 'LMK');
            file_put_contents($image, file_get_contents($movie->getCoverURL()));
            
            /* send message to sns. */
            $oauth = $currentAccount->getOAuthInformation();
            if ( null !== $oauth && 'QQ' === $oauth->get('server_name') ) {
                /* @var $QQService QQService */
                $QQService = X::system()->getServiceManager()->get(QQService::getServiceName());
                $QQConnect = $QQService->getConnect();
                $QQConnect->setOpenId($oauth->get('openid'));
                $QQConnect->setAccessToken($oauth->get('access_token'));
                $QQConnect->Tweet()->addWithPicture($message, $image);
            }
            unlink($image);
        }
        
        /* redirect if needed. */
        if ( $redirect ) {
            $this->goBack();
        }
    }
}