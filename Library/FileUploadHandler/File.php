<?php
namespace X\Library\FileUploadHandler;
/**
 * 
 */
class File {
    /**
     * @var array
     */
    private $uploadInfo = array();
    
    /**
     * @param array $uploadInfo
     */
    public function __construct( $uploadInfo ) {
        $this->uploadInfo = $uploadInfo;
    }
    
    /**
     * @return string
     */
    public function getName() {
        return $this->uploadInfo['name'];
    }
    /**
     * @return integer
     */
    public function getSize() {
        return (int)$this->uploadInfo['size'];
    }
    
    /**
     * @return string
     */
    public function getType(){
        return $this->uploadInfo['type'];
    }
    
    /**
     * @return string
     */
    public function getTempName(){
        return $this->uploadInfo['tmp_name'];
    }
    
    /**
     * @return integer
     */
    public function getError(){
        return (int)$this->uploadInfo['error'];
    }
    
    /**
     * @return boolean
     */
    public function hasError(){
        return 0!==$this->getError();
    }
    
    /**
     * @return string
     */
    public function getErrorMessage() {
        switch ( $this->getError() ) {
        case UPLOAD_ERR_OK         : return '文件上传成功。'; 
        case UPLOAD_ERR_INI_SIZE   : return '文件大小超过服务器允许上传的最大值。';
        case UPLOAD_ERR_FORM_SIZE  : return '文件大小超过HTML表单指定的值 。';
        case UPLOAD_ERR_PARTIAL    : return '文件只有部分被上传 。';
        case UPLOAD_ERR_NO_FILE    : return '没有文件被上传。';
        case UPLOAD_ERR_NO_TMP_DIR : return '没有找不到临时文件夹 。';
        case UPLOAD_ERR_CANT_WRITE : return '文件写入失败 。';
        case UPLOAD_ERR_EXTENSION  : return '文件上传扩展没有打开 。';
        }
    }
    
    /**
     * @return \X\Library\FileUploadHandler\Validator
     */
    public function getValidator(){
        return new Validator($this);
    }
    
    /**
     * @param string $targetPath
     * @return boolean
     */
    public function move($targetPath){
        return move_uploaded_file($this->getTempName(), $targetPath);
    }
}