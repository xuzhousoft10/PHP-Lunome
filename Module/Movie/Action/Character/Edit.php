<?php
namespace X\Module\Movie\Action\Character;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\JSON;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * The action class for movie/character/edit action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Edit extends JSON { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $movie, $character ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($movie);
        if ( null === $movie ) {
            return $this->error('Movie does not exists.');
        }
        
        if ( !isset($_FILES['image']) || 0!==$_FILES['image']['error'] ) {
            return $this->error('Character\'s photo is required.');
        }
        
        $image = tempnam(sys_get_temp_dir(), 'UPCI');
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
        
        $characterManager = $movie->getCharacterManager();
        $characterManager->add()
            ->set('name', $character['name'])
            ->set('description', $character['description'])
            ->setPhoto($image)
            ->save();
        
        unlink($image);
        return $this->success();
    }
}