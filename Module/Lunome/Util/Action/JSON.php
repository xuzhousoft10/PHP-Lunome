<?php
namespace X\Module\Lunome\Util\Action;
/**
 * 
 */
abstract class JSON extends \X\Util\Action\Basic {
    /**
     * @var array 
     */
    private $response = array(
        'success'   => true,
        'message'   => '',
        'data'      => array(),
    );
    
    /**
     * @param string $message
     * @param array $data
     */
    protected function error( $message, $data=array() ) {
        return $this->setupResponse(false, $message, $data);
    }
    
    /**
     * @param string $message
     * @param array $data
     */
    protected function success( $message='', $data=array() ) {
        return $this->setupResponse(true, $message, $data);
    }
    
    /**
     * @param boolean $isSuccessed
     * @param string $message
     * @param array $data
     */
    private function setupResponse( $isSuccessed, $message, $data ) {
        $this->response['success']  = $isSuccessed;
        $this->response['message']  = $message;
        $this->response['data']     = $data;
        echo json_encode($this->response);
        return $isSuccessed;
    }
}