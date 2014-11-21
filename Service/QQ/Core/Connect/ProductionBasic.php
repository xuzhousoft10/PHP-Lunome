<?php
/**
 *
 */
namespace X\Service\QQ\Core\Connect;

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
    
    /**
     * 通过指定API名称进行调用。
     * @param string $api       API名称
     * @param array $params     传递给API的参数
     * @param string $isGet     是否使用GET方式
     * @throws Exception        当请求出错时抛出异常
     * @return array
     */
    protected function doRequest( $api, $params=array(), $isGet=true  ) {
        $url = sprintf('https://graph.qq.com/%s', $api);
        $params = array_filter($params);
        if ( $isGet ) {
            $result = $this->httpGetJSON($url, $params);
        } else {
            $result = $this->httpPostJSON($url, $params);
        }
        return $this->checkResponse($result);
    }
    
    /**
     * 检查请求结果是否出错。并将没有错误的结果返回。
     * @param array $response
     * @throws Exception
     * @return array
     */
    protected function checkResponse( $response ) {
        if ( 0 === $response['ret']*1 ) {
            return $response;
        } else {
            throw new Exception($response['msg'], $response['ret']*1);
        }
    }
}