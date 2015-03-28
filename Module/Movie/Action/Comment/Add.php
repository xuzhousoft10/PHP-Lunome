<?php
namespace X\Module\Movie\Action\Comment;
/**
 * 
 */
use X\Core\X;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Lunome\Util\Action\JSON;
/**
 * 
 */
class Add extends JSON { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id, $content ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        if ( !$movieService->has($id) ) {
            return $this->error('Movie does not exists.');
        }
        
        $movieAccount = $movieService->getCurrentAccount();
        $movieAccount->addShortComment($id)->set('content', $content)->save();
        return $this->success();
    }
}