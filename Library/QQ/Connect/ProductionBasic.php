<?php
/**
 *
 */
namespace X\Library\QQ\Connect;

/**
 * 
 */
class ProductionBasic {
    /**
     * SDK框架实例
     * @var SDK
     */
    private $sdk = null;
    
    /**
     * 构造当前产品API实例
     * @param SDK $sdk
     */
    public function __construct( SDK $sdk ) {
        $this->sdk = $sdk;
    }
    
    /**
     * 获取GET请求结果并转换json结果至数组
     * @param string $url
     * @param array $parms
     * @return array
     */
    protected function httpGetJSON( $url, array $parms=array() ) {
        $parameters = array();
        $parameters['oauth_consumer_key']    = SDK::$appid;
        $parameters['access_token']          = $this->sdk->getAccessToken();
        $parameters['openid']                = $this->sdk->getOpenId();
        $parameters['format']                = 'JSON';
        $parameters = array_merge($parameters, $parms);
        $request = new Request($url, $parameters);
        return $request->get(Request::FOTMAT_JSON);
    }
    
    /**
     * 获取POST请求结果并转换json结果至数组
     * @param string $url
     * @param array $parms
     * @return array
     */
    protected function httpPostJSON ( $url, array $parms=array() ) {
        $parameters = array();
        $parameters['oauth_consumer_key']    = SDK::$appid;
        $parameters['access_token']          = $this->sdk->getAccessToken();
        $parameters['openid']                = $this->sdk->getOpenId();
        $parameters['format']                = 'JSON';
        $parameters = array_merge($parameters, $parms);
        $request = new Request($url, $parameters);
        return $request->post(Request::FOTMAT_JSON);
    }
}