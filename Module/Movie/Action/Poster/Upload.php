<?php
namespace X\Module\Movie\Action\Poster;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * The action class for movie/poster/upload action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Upload extends Basic { 
    /**
     * @param string $id The id of the movie.
     */
    public function runAction( $id ) {
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
        $moduleConfig = $this->getModule()->getConfiguration();
        $movie = $movieService->get($id);
        
        if ( null === $movie ) {
            $this->responseError('电影不存在。');
        }
        
        if ( !isset($_FILES['poster']) ) {
            $this->responseError('上传文件不存在');
        }
        
        $allowedPosterType = $moduleConfig->get('movie_poster_file_type');
        if ( !in_array($_FILES['poster']['type'], $allowedPosterType) ) {
            $this->responseError('海报文件格式不正确。');
        }
        
        if ( $_FILES['poster']['error'] > 0 ) {
            $this->responseError('文件上传失败。');
        }
        
        $tmpFile = tempnam(sys_get_temp_dir(), 'MPUP');
        move_uploaded_file($_FILES['poster']['tmp_name'],$tmpFile);
        $movie->getPosterManager()->add()->setImage($tmpFile)->save();
        unlink($tmpFile);
        
        echo json_encode(array('status'=>'success'));
    }
    
    /**
     * @param unknown $message
     */
    private function responseError( $message ) {
        echo json_encode(array('error'=>$message));
        X::system()->stop();
    }
}