<?php
namespace X\Module\Movie\Action\Character;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * The action class for movie/character/edit action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Edit extends Basic { 
    /**
     * @param string $movie
     * @param array $character
     */
    public function runAction( $movie, $character ) {
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $movie = $movieService->get($movie);
        if ( null === $movie ) {
            return;
        }
        
        $characterManager = $movie->getCharacterManager();
        $image = null;
        if ( isset($_FILES['image']) && 0===$_FILES['image']['error'] ) {
            $tmpname = tempnam(sys_get_temp_dir(), 'UPCI');
            move_uploaded_file($_FILES['image']['tmp_name'], $tmpname);
            $image = $tmpname;
        }
        $characterManager->add()
            ->set('name', $character['name'])
            ->set('description', $character['description'])
            ->setPhoto($image)
            ->save();
        if ( null !== $image ) {
            unlink($image);
        }
        echo json_encode(array('status'=>'ok'));
    }
}