<?php
/**
 * 
 */
namespace X\Service\Sina\Core\Connect;

/**
 * 
 */
use X\Core\Util\HttpRequest;

/**
 * 
 */
class SDK {
    /**
     * 申请应用时分配的AppKey。
     * @var string
     */
    public $AppID = null;
    
    /**
     * @var string
     */
    public $AppSecret = null;
    
    /**
     * 接口获取授权后的access token
     * @var string
     */
    public $accessToken = null;
    
    /**
     * @var unknown
     */
    public $accessTokenLifeTime = null; 
    
    /**
     * @var unknown
     */
    public $uid = null;
    
    /**
     * @param unknown $callbackURL
     * @param string $responseType
     * @param string $state
     * @param string $display
     * @return string
     */
    public function getAuthorizeURL( $callbackURL, $responseType='code', $state=null, $display=null ) {
        $params                     = array();
        $params['client_id']        = $this->AppID;
        $params['redirect_uri']     = $callbackURL;
        $params['response_type']    = $responseType;
        $params['state']            = $state;
        $params['display']          = $display;
        return self::AUTHORIZE_URL.'?'.http_build_query($params);
    }
    
    /**
     * @param unknown $code
     * @param unknown $redirectURI
     * @throws OAuthException
     * @return Ambigous <multitype:, mixed>
     */
    function handleCallbackByCode($code, $redirectURI ) {
        $request = new HttpRequest(self::ACCESS_TOKEN_URL);
        $request->addParameter('client_id', $this->AppID);
        $request->addParameter('client_secret', $this->AppSecret);
        $request->addParameter('grant_type', 'authorization_code');
        $request->addParameter('code', $code);
        $request->addParameter('redirect_uri', $redirectURI);
        $request->url = $request->getGetURL();
        $request->post();
        $token = $request->readJson();
        
        if ( is_array($token) && !isset($token['error']) ) {
            $this->accessToken = $token['access_token'];
            $this->accessTokenLifeTime = $token['expires_in'];
            $this->uid = $token['uid'];
        } else {
            throw new Exception("get access token failed." . $token['error']);
        }
    }
    
    /**
     * 
     * @var \X\Service\Sina\Core\Connect\User
     */
    private $user = null;
    
    /**
     * @return \X\Service\Sina\Core\Connect\User
     */
    public function User() {
        if ( null === $this->user ) {
            $this->user = new User($this);
        }
        return $this->user;
    }
    
    /**
     * @var unknown
     */
    private $status = null;
    
    /**
     * @return \X\Service\Sina\Core\Connect\Status
     */
    public function Status() {
        if ( null === $this->status ) {
            $this->status = new Status($this);
        }
        return $this->status;
    }
    
    const AUTHORIZE_URL = 'https://api.weibo.com/oauth2/authorize';
    const ACCESS_TOKEN_URL = 'https://api.weibo.com/oauth2/access_token';
}