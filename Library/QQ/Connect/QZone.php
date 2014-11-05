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
        $url = 'https://graph.qq.com/user/get_user_info';
        return $this->httpGetJSON($url);
    }
    
    /* 下列方法由于暂时没有申请成功所以无法测试。 */
//     public function addAlbum() {}
//     public function uploadPicture() {}
//     public function listAlbum() {}
//     public function checkPageFans() {}
}