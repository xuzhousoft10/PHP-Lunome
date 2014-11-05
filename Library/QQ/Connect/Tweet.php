<?php
/**
 *
 */
namespace X\Library\QQ\Connect;

/**
 * 
 */
class Tweet extends ProductionBasic {
    /**
     * 获取腾讯微博登录用户的用户资料。
     * @return array
     */
    public function getInfo() {
        $url = 'https://graph.qq.com/user/get_info';
        return $this->httpGetJSON($url);
    }
    
    /**
     * 发表一条微博信息（纯文本）到腾讯微博平台上。
     * 注意连续两次发布的微博内容不可以重复。
     * @param string $content           表示要发表的微博内容。必须为UTF-8编码，最长为140个汉字，
     *                                  也就是420字节。如果微博内容中有URL，后台会自动将该URL转换为短URL，
     *                                  每个URL折算成11个字节。若在此处@好友，需正确填写好友的微博账号，而非昵称。
     * @param string $clientIp          用户ip。必须正确填写用户侧真实ip，不能为内网ip及以127或255开头的ip，以分析用户所在地。
     * @param string $longitude         用户所在地理位置的经度。为实数，最多支持10位有效数字。有效范围：-180.0到+180.0，+表示东经，默认为0.0。
     * @param string $latitude          用户所在地理位置的纬度。为实数，最多支持10位有效数字。有效范围：-90.0到+90.0，+表示北纬，默认为0.0。
     * @param string $compatibleflag    容错标志，支持按位操作，默认为0。0x20：微博内容长度超过140字则报错；0：以上错误均做容错处理，即发表普通微博。
     * @return array
     */
    public function add($content, $clientIp=null, $longitude=null, $latitude=null, $compatibleflag=null) {
        $url = 'https://graph.qq.com/t/add_t';
        $params = array();
        $params['content'] = $content;
        if ( null !== $clientIp )   $params['clientip'] = $clientIp;
        if ( null !== $longitude )  $params['longitude'] = $longitude;
        if ( null !== $latitude )   $params['latitude'] = $latitude;
        if ( null !== $compatibleflag ) $params['compatibleflag'] = $compatibleflag;
        return $this->httpPostJSON($url, $params);
    }
    
    public function addWithPicture() {}
    public function delete() {}
    public function getRepostList() {}
    public function getUserInfo() {}
    public function getFansList() {}
    public function getIdolList() {}
    public function addIdol() {}
    public function deleteIdol() {}
}