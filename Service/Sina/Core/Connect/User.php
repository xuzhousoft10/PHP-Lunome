<?php
namespace X\Service\Sina\Core\Connect;

use X\Core\Util\HttpRequest;
class User {
    /**
     * @var \X\Service\Sina\Core\Connect\SDK
     */
    private $connect = null;
    
    /**
     * @param unknown $connect
     */
    public function __construct( $connect ) {
        $this->connect = $connect;
    }
    
    /**
     * @return array
     */
    public function getInfo() {
        $request = new HttpRequest('https://api.weibo.com/2/users/show.json');
        $request->addParameter('access_token', $this->connect->accessToken);
        $request->addParameter('uid', $this->connect->uid);
        $request->get();
        $result = $request->readJson();
        return $result;
    }
}