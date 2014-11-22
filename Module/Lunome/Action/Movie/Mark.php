<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Media\Mark as MediaMark;
use X\Module\Lunome\Service\User\Service as UserService;
use X\Module\Lunome\Service\Movie\Service as MovieService;

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
        $media = $this->getMediaService()->get($this->mediaId);
        $message = $this->getMessageContentByMark($media, $this->mark);
        $userService->getQQConnect()->Tweet()->add($message);
        
        parent::afterRunAction();
    }
    
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