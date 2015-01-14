<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Service\User\Account;
use X\Module\Lunome\Util\Action\Media\Mark as MediaMark;
use X\Module\Lunome\Service\User\Service as UserService;
use X\Module\Lunome\Service\Movie\Service as MovieService;
use X\Module\Lunome\Model\Account\AccountOauth20Model;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Mark extends MediaMark { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Action::afterRunAction()
     */
    protected function afterRunAction() {
        /* @var $userService UserService */
        $userService = X::system()->getServiceManager()->get(UserService::getServiceName());
        $isAutoShare = $userService->getAccount()->getConfiguration(Account::SETTING_TYPE_SNS, 'auto_share', '1');
        $isAutoShare = '1' === $isAutoShare;
        
        if ( $isAutoShare ) {
            $mediaService = $this->getMediaService();
            $media = $mediaService->get($this->mediaId);
            $message = $this->getMessageContentByMark($media, $this->mark);
            $image = false;
            if ( 1 === $media['has_cover']*1 ) {
                $tmpName = tempnam(sys_get_temp_dir(), 'LMK');
                file_put_contents($tmpName, file_get_contents($mediaService->getCoverURL($media['id'])));
                $image = $tmpName;
            }
            
            $oauth = $userService->getAccount()->getOauth();
            if ( AccountOauth20Model::SERVER_QQ === $oauth['server'] ) {
                if ( false === $image ) {
                    $userService->getQQConnect()->Tweet()->add($message);
                } else {
                    $userService->getQQConnect()->Tweet()->addWithPicture($message, $tmpName);
                }
            } else if ( AccountOauth20Model::SERVER_SINA === $oauth['server'] ) {
                if ( false === $image ) {
                    $userService->getWeiboConnect()->Status()->update($message);
                } else {
                    $userService->getWeiboConnect()->Status()->upload($message, $image);
                }
            }
            
            if ( false !== $image ) {
                unlink($image);
            }
        }
        
        parent::afterRunAction();
    }
    
    /**
     * @param unknown $media
     * @param unknown $mark
     * @return string
     */
    private function getMessageContentByMark( $media, $mark ) {
        $message = '';
        switch ( $mark ) {
        case MovieService::MARK_INTERESTED :
            $message = sprintf('感觉《%s》会挺好看的～～～，小伙伴们都去瞅瞅吧。', $media['name']);
            break;
        case MovieService::MARK_WATCHED:
            $message = sprintf('看完了《%s》，小伙伴们也去感受感受吧。', $media['name']);
            break;
        case MovieService::MARK_IGNORED:
            $message = sprintf('《%s》貌似不好看的样子～～～', $media['name']);
            break;
        }
        $message .= "\n";
        $message .= sprintf('http://www.lunome.com/?module=lunome&action=movie/detail&id=%s', $media['id']);
        return $message;
    }
}