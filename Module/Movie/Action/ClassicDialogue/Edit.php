<?php
namespace X\Module\Movie\Action\ClassicDialogue;
/**
 * 
 */
use X\Module\Lunome\Util\Action\JSON;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * 
 */
class Edit extends JSON { 
    /**
     * @param string $id
     * @param string $content
     */
    public function runAction( $id, $content ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($id);
        if ( null === $movie ) {
            return $this->error('Movie does not exists.');
        }
        
        $movie->getClassicDialogueManager()->add()->set('content', $content)->save();
        $this->success();
    }
}