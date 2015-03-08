<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie;
/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Lunome\Service\User\Account;
use X\Module\Lunome\Service\Movie\Service as MovieService;
use X\Module\Lunome\Model\Account\AccountOauth20Model;

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
        $movieService = $this->getMovieService();
        
        /* check movie exists. */
        if ( !$movieService->has($id) ) {
            if ( $redirect ) {
                $this->throw404();
            } else {
                echo json_encode(array('error'=>'movie does not exists.'));
                return ;
            }
        }
        
        /* check if the mark available.*/
        $mark = intval($mark);
        $marks = $movieService->getMarkNames();
        if ( !isset($marks[$mark]) ) {
            return;
        }
        
        /* do mark. */
        $movieService->mark($id, $mark);
        
        /* public mark action to sns if needed. */
        $userService = $this->getUserService();
        $isAutoShare = $userService->getAccount()->getConfiguration(Account::SETTING_TYPE_SNS, 'auto_share', '1');
        $isAutoShare = '1' === $isAutoShare;
        
        if ( $isAutoShare ) {
            $media = $movieService->get($id);
            $message = '';
            switch ( $mark ) {
            case MovieService::MARK_INTERESTED  : $message = '感觉《'.$media['name'].'》会挺好看的～～～，小伙伴们都去瞅瞅吧。';break;
            case MovieService::MARK_WATCHED     : $message = '看完了《'.$media['name'].'》，小伙伴们也去感受感受吧。';break;
            case MovieService::MARK_IGNORED     : $message = '《'.$media['name'].'》貌似不好看的样子～～～';break;
            default:$message='';break;
            }
            $message .= "\n";
            $message .= 'http://'.$_SERVER['HTTP_HOST'].'/?module=lunome&action=movie/detail&id='.$media['id'];
            $image = false;
            if ( 1 === $media['has_cover']*1 ) {
                $tmpName = tempnam(sys_get_temp_dir(), 'LMK');
                file_put_contents($tmpName, file_get_contents($movieService->getCoverURL($media['id'])));
                $image = $tmpName;
            }
            
            /* send message to sns. */
            $oauth = $userService->getAccount()->getOauth();
            if ( null !== $oauth && AccountOauth20Model::SERVER_QQ === $oauth['server'] ) {
                if ( false === $image ) {
                    $userService->getQQConnect()->Tweet()->add($message);
                } else {
                    $userService->getQQConnect()->Tweet()->addWithPicture($message, $tmpName);
                }
            }
            
            if ( false !== $image ) {
                unlink($image);
            }
        }
        
        /* redirect if needed. */
        if ( $redirect ) {
            $this->goBack();
        }
    }
}