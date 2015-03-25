<?php
namespace X\Module\Movie\Action\ClassicDialogue;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * The action class for movie/classicDialogue/edit action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Edit extends Basic { 
    /**
     * @param string $id
     * @param string $content
     */
    public function runAction( $id, $content ) {
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $movie = $movieService->get($id);
        $movie->getClassicDialogueManager()->add()->set('content', $content)->save();
        echo json_encode(array('status'=>'success'));
    }
}