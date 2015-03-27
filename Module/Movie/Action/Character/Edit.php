<?php
namespace X\Module\Movie\Action\Character;
/**
 * 
 */
use X\Core\X;
use X\Library\XMath\Number;
use X\Module\Lunome\Util\Action\JSON;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Library\FileUploadHandler\Handler as FileUploadHandler;
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
        $photo = $this->getPhoto();
        if ( false === $photo ) {
            return false;
        }
        
        $characterManager = $movie->getCharacterManager();
        $characterManager->add()
            ->set('name', $character['name'])
            ->set('description', $character['description'])
            ->setPhoto($photo->getPath())
            ->save();
        
        $photo->delete();
        return $this->success();
    }
    
    /**
     * @return \X\Library\FileUploadHandler\File
     */
    private function getPhoto() {
        $moduleConfiguration = $this->getModule()->getConfiguration();
        $uploadHandler = FileUploadHandler::setup('image');
        
        if ( !$uploadHandler->hasFile() ) {
            return $this->error('Character\'s photo is required.');
        }
        $photo = $uploadHandler->getFile();
        if ( $photo->hasError() ) {
            return $this->error($photo->getErrorMessage());
        }
        
        $photoValidator = $photo->getValidator();
        $photoMaxSize = $moduleConfiguration->get('movie_character_photo_max_size');
        $photoValidator->setMaxSize($photoMaxSize);
        if ( !$photoValidator->validateMaxSize() ) {
            return $this->error('Character\'s photo can not over '.Number::formatAsFileSize($photoMaxSize).'.');
        }
        
        $photoTypes = $moduleConfiguration->get('movie_character_photo_types');
        $photoValidator->setTypes($photoTypes);
        if ( !$photoValidator->validateType() ) {
            return $this->error('Character\'s photo only support '.implode(',', array_keys($photoTypes)).'.');
        }
        
        $photo->moveToTempPath();
        return $photo;
    }
}