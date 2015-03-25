<?php
namespace X\Module\Movie\Action\Comment;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * The action class for movie/comment/add action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Add extends Basic { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id, $content ) {
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $movieAccount = $movieService->getCurrentAccount();
        $movieAccount->addShortComment($id)->set('content', $content)->save();
    }
}