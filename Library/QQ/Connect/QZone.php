<?php
/**
 *
 */
namespace X\Library\QQ\Connect;

/**
 * 
 */
class QZone {
    /**
     * 该变量所属框架的实例。
     * @var SDK
     */
    private $sdk = null;
    
    /**
     * 构造该实例
     * @param SDK $sdk
     */
    public function __construct( SDK $sdk ) {
        $this->sdk = $sdk;
    }
    
    /**
     * 获取网站登录用户信息，目前可获取用户在QQ空间的昵称、头像信息及黄钻信息。
     * @return array
     */
    public function getInfo() {
        $params['oauth_consumer_key']   = SDK::$appid;
        $params['access_token']         = $this->sdk->getAccessToken();
        $params['openid']               = $this->sdk->getOpenId();
        $params['format']               = 'JSON';
        $request = new Request('https://graph.qq.com/user/get_user_info', $params);
        $userInfo = $request->get(Request::FOTMAT_JSON);
        return $userInfo;
    }
    
    public function addAlbum() {}
    public function uploadPicture() {}
    public function listAlbum() {}
    public function addShare() {}
    public function checkPageFans() {}
}