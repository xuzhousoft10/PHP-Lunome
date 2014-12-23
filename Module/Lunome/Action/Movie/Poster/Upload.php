<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie\Poster;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/ignore action.
 * @author Unknown
 */
class Upload extends Basic { 
    /**
     * @param unknown $id
     * @param unknown $content
     */
    public function runAction( $id ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        if ( !isset($_FILES['poster']) ) {
            $this->responseError('上传文件不存在');
        }
        
        $allowedPosterType = array('image/png', 'image/jpeg');
        if ( !in_array($_FILES['poster']['type'], $allowedPosterType) ) {
            $this->responseError('海报文件格式不正确。');
        }
        
        if ( $_FILES['poster']['error'] > 0 ) {
            $this->responseError('文件上传失败。');
        }
        
        $tmpFile = tempnam(sys_get_temp_dir(), 'MPUP');
        move_uploaded_file($_FILES['poster']['tmp_name'],$tmpFile);
        $movieService->addPoster($id, $tmpFile);
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