<?php
namespace X\Module\Movie\Action\Poster;
/**
 * 
 */
use X\Module\Lunome\Util\Action\JSON;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Library\FileUploadHandler\Handler as FileUploadHandler;
/**
 * The action class for movie/poster/upload action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Upload extends JSON { 
    /**
     * (non-PHPdoc)
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $id ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movie = $movieService->get($id);
        if ( null === $movie ) {
            return $this->error('Movie does not exists.');
        }
        
        $uploadHandler = FileUploadHandler::setup('poster');
        if ( !$uploadHandler->hasFile() ) {
            return $this->error('Poster image is required.');
        }
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $allowedPosterType = $moduleConfig->get('movie_poster_file_type');
        $poster = $uploadHandler->getFile();
        if ( $poster->hasError() ) {
            return $this->error('Filed to upload poster.');
        }
        
        $posterValidator = $poster->getValidator();
        $posterValidator->setTypes($allowedPosterType);
        if ( !$posterValidator->validateType() ) {
            return $this->error('Poster file type is not supported.');
        }
        
        $poster->moveToTempPath();
        $movie->getPosterManager()->add()->setImage($poster->getPath())->save();
        $poster->delete();
        
        return $this->success();
    }
}