<?php
/**
 * 
 */
namespace X\Service\QQ\Core\Connect;

/**
 * 
 */
class SDK {
    /**
     * 应用的唯一标识。在OAuth2.0认证过程中，appid的值即为oauth_consumer_key的值。
     * @var string
     */
    public static $appid = null;
    
    /**
     * appid对应的密钥，访问用户资源时用来验证应用的合法性。在OAuth2.0认证过程中，
     * appkey的值即为oauth_consumer_secret的值。
     * @var string
     */
    public static $appkey = null;
    
    /**
     * 成功授权后的回调地址，必须是注册appid时填写的主域名下的地址，建议设置为网站首页或网站的用户中心。
     * 注意需要将url进行URLEncode。
     * @var string
     */
    public static $callback = null;
    
    /**
     * 请求用户授权时向用户显示的可进行授权的列表。
     * 可填写的值是API文档中列出的接口，以及一些动作型的授权（目前仅有：do_like），
     * 如果要填写多个接口名称，请用逗号隔开。
     * 例如：scope=get_user_info,list_album,upload_pic,do_like
     * 不传则默认请求对接口get_user_info进行授权。
     * 建议控制授权项的数量，只传入必要的接口名称，因为授权项越多，用户越可能拒绝进行任何授权。
     * @var string
     */
    public static $scope = 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo,check_page_fans,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idolist,add_idol,del_idol,get_tenpay_addr1';
    
    /**
     * Access Token 信息， 包括当前token， 有效期和refresh token.
     * @var array
     */
    private $token = array();
    
    /**
     * 该方法用于在没有授权时获取授权界面的URL。
     */
    public function getLoginUrl(){
        /* 跳转到授权页面的参数 */
        $request = $this->getRequest(self::URL_AUTH_CODE, array(
            'response_type' => 'code',
            'client_id'     => self::$appid,
            'redirect_uri'  => urlencode(self::$callback),
            'scope'         => self::$scope,
        ));
        return $request->toString();
    }
    
    /**
     * 当前用户的Open ID
     * @var string
     */
    private $openId = null;
    
    /**
     * 当登录成功后， 你必须在回调页面调用该方法来获取access token 和open ID。
     * 该方法仅能在回调页面执行。
     *
     * @throws Exception 当CSRF随机串与会话中存储的不一致的时候。
     */
    public function setup(){
        /* 获取access token */
        $request = $this->getRequest(self::URL_ACCESS_TOKEN, array(
            'grant_type'    => 'authorization_code',
            'client_id'     => self::$appid,
            'redirect_uri'  => urlencode(self::$callback),
            'client_secret' => self::$appkey,
            'code'          => $_GET['code']
        ));
        
        /* check error first. */
        $this->token = $request->get(Request::FORMAT_JS_CALLBACK_JSON);
        if ( null !== $this->token && isset($this->token['error']) ) {
            throw new Exception($this->token['error_description'], $this->token['error']);
        }
        
        $this->token = $request->get(Request::FORMAT_URL_PARAM);
        $this->token['expires_in'] = date('Y-m-d H:i:s',strtotime("{$this->token['expires_in']} second"));
        
        /* 获取Open ID */
        $request = $this->getRequest(self::URL_OPENID, array('access_token' => $this->token['access_token']));
        $response = $request->get();
        $lpos = strpos($response, '(');
        $rpos = strrpos($response, ')');
        $response = substr($response, $lpos + 1, $rpos - $lpos -1);
        $user = json_decode($response);
        $this->openId = $user->openid;
    }
    
    /**
     * 获取一个Request实例
     * @param string $url
     * @param array $parameters
     * @return \X\Library\QQ\Connect\Request
     */
    private function getRequest($url, array $parameters=array()) {
        $request = new Request($url, $parameters);
        return $request;
    }
    
    /**
     * 获取access token
     * @return string
     */
    public function getAccessToken(){
        return $this->token['access_token'];
    }
    
    /**
     * @param unknown $token
     */
    public function setAccessToken( $token ) {
        $this->token['access_token'] = $token;
    }
    
    /**
     * 获取Access Token 信息， 包括当前token， 有效期和refresh token.
     * @return array
     */
    public function getTokenInfo() {
        return $this->token;
    }
    
    /**
     * OpenID是此网站上或应用中唯一对应用户身份的标识，
     * 网站或应用可将此ID进行存储，便于用户下次登录时辨识其身份，
     * 或将其与用户在网站上或应用中的原有账号进行绑定。
     * @return string
     */
    public function getOpenId(){
        return $this->openId;
    }
    
    /**
     * @param unknown $id
     */
    public function setOpenId( $id ) {
        $this->openId = $id;
    }
    
    /**
     * 该变量保存着QZone的实例。
     * @var QZone
     */
    private $qzone = null;
    
    /**
     * 获取Qzone实例。
     * @return QZone
     */
    public function QZone() {
        if ( null === $this->qzone ) {
            $this->qzone = new QZone($this);
        }
        return $this->qzone;
    }
    
    /**
     * 保存当前SDK中的微博实例
     * @var Tweet
     */
    private $tweet = null;
    
    /**
     * 获取微博实例。
     * @return Tweet
     */
    public function Tweet() {
        if ( null === $this->tweet ) {
            $this->tweet = new Tweet($this);
        }
        return $this->tweet;
    }
    
    /**
     * 保存当前SDK中的财付通实例
     * @var Tenpay
     */
    private $tenpay = null;
    
    /**
     * 获取财付通实例
     * @return Tenpay
     */
    public function Tenpay() {
        if ( null === $this->tenpay ) {
            $this->tenpay = new Tenpay($this);
        }
        return $this->tenpay;
    }
    
    /**
     * 当前API的版本信息。
     * @var string
     */
    const VERSION = "2.0";
    
    /**
     * 用来获取Open ID的链接
     * @var string
     */
    const URL_OPENID = "https://graph.qq.com/oauth2.0/me";
    
    /**
     * 用来获取访问码的链接。
     * @var string
     */
    const URL_ACCESS_TOKEN = "https://graph.qq.com/oauth2.0/token";
    
    /**
     * 用来获取授权码的链接。
     * @var string
     */
    const URL_AUTH_CODE = "https://graph.qq.com/oauth2.0/authorize";
}