<?php 
namespace X\Service\Sina\Core\Connect;
use X\Core\Util\HttpRequest;
class Status {
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
     * @param unknown $text
     * @param unknown $image
     */
    public function upload( $text, $image ) {
        $request = new HttpRequest('https://upload.api.weibo.com/2/statuses/upload.json');
        $request->addParameter('access_token', $this->connect->accessToken);
        $request->addParameter('status', urlencode($text));
        $request->addParameter('pic', '@'.$image);
        $request->post();
        $result = $request->readJson();
        return $result;
    }
    
    /**
     * @param unknown $text
     * @return Ambigous <multitype:, mixed>
     */
    public function update( $text ) {
        $request = new HttpRequest('https://upload.api.weibo.com/2/statuses/upload.json');
        $request->addParameter('access_token', $this->connect->accessToken);
        $request->addParameter('status', urlencode($text));
        $request->post();
        $result = $request->readJson();
        return $result;
    }
}