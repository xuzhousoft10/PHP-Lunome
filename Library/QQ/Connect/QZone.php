<?php
/**
 *
 */
namespace X\Library\QQ\Connect;

/**
 * 
 */
class QZone extends ProductionBasic {
    /**
     * 获取网站登录用户信息，目前可获取用户在QQ空间的昵称、头像信息及黄钻信息。
     * @return array
     */
    public function getInfo() {
        return $this->doRequest('user/get_user_info');
    }
    
    /**
     * 获取已登录用户的关于QQ会员业务的基本资料。
     * @return array
     */
    public function getVIPInfo() {
        return $this->doRequest('user/get_vip_info');
    }
    
    /**
     * 获取已登录用户的关于QQ会员业务的详细资料。
     * @return array
     */
    public function getVIPExtInfo() {
        return $this->doRequest('user/get_vip_rich_info');
    }
    
    /**
     * 获取登录用户的相册列表。
     * @return array
     */
    public function albums() {
        return $this->doRequest('photo/list_album');
    }
    
    /**
     * 登录用户上传照片
     * @param string $picture       上传照片的文件名
     * @param string $albumid       相册id
     * @param string $title         照片的命名，必须以.jpg, .gif, .png, .jpeg, .bmp此类后缀结尾。
     * @param string $desc          照片描述，注意照片描述不能超过200个字符。
     * @param boolean $needfeed     标识上传照片时是否要发feed（0：不发feed； 1：发feed）。 如果不填则默认为发feed。
     * @param string $x             照片拍摄时的地理位置的经度。请使用原始数据（纯经纬度，0-360）。
     * @param string $y             照片拍摄时的地理位置的纬度。请使用原始数据（纯经纬度，0-360）。
     * @return array
     */
    public function uploadPicture( $picture, $albumid=null, $title=null, $desc=null, $needfeed=null, $x=null, $y=null ) {
        $params = array();
        $params['picture']  = sprintf('@%s', $picture);
        $params['albumid']  = $albumid;
        $params['title']    = $title;
        $params['photodesc']= $desc;
        $params['needfeed'] = $needfeed;
        $params['x']        = $x;
        $params['y']        = $y;
        return $this->doRequest('photo/upload_pic', $params, false);
    }
    
    /**
     * 登录用户创建相册。
     * @param string $name      相册名，不能超过30个字符。
     * @param string $desc      相册描述，不能超过200个字符。
     * @param integer $priv     相册权限，其取值含义为： 
     *                          1=公开；
     *                          3=只主人可见； 
     *                          4=QQ好友可见； 
     *                          5=问答加密。 
     *                          不传则相册默认为公开权限。
     * @param string $question  问题，不能超过30个字符。
     * @param string $answer    答案，不能超过30个字符。
     */
    public function addAlbums( $name, $desc=null, $priv=null, $question=null, $answer=null ) {
        $params = array();
        $params['albumname']    = $name;
        $params['albumdesc']    = $desc;
        $params['priv']         = $priv;
        $params['question']     = $question;
        $params['answer']       = $answer;
        return $this->doRequest('photo/add_album', $params, false);
    }
    
    /**
     * 获取登录用户的照片列表。
     * @param string $album 表示要获取的照片列表所在的相册ID。
     * @return array
     */
    public function photos( $album ) {
        return $this->doRequest('photo/list_photo', array('albumid'=>$album));
    }
}