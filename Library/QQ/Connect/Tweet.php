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
        $result = $this->httpPostJSON($url, $params);
        if ( 0 === $result['errcode']*1 ) {
            return $result['data']['id'];
        } else {
            throw new Exception($result['msg']);
        }
    }
    
    /**
     * 根据微博ID删除指定微博。
     * @param string $id 微博消息的ID，用来唯一标识一条微博消息。
     * @return array
     */
    public function delete( $id ) {
        $url = 'https://graph.qq.com/t/del_t';
        $params = array('id'=>$id);
        $result = $this->httpPostJSON($url, $params);
        if ( 0 !== $result['errcode']*1 ) {
            throw new Exception($result['msg']);
        }
    }
    
    /**
     * 上传一张图片，并发布一条消息到腾讯微博平台上。
     * @param string $content           表示要发表的微博内容。必须为UTF-8编码，最长为140个汉字，也就是420字节。
     *                                  如果微博内容中有URL，后台会自动将该URL转换为短URL，每个URL折算成11个字节。
     *                                  若在此处@好友，需正确填写好友的微博账号，而非昵称。
     * @param string $pic               图片路径
     * @param string $clientIp          用户ip。必须正确填写用户侧真实ip，不能为内网ip及以127或255开头的ip，以分析用户所在地。
     * @param string $longitude         用户所在地理位置的经度。为实数，最多支持10位有效数字。有效范围：-180.0到+180.0，+表示东经，默认为0.0。
     * @param string $latitude          用户所在地理位置的纬度。为实数，最多支持10位有效数字。有效范围：-90.0到+90.0，+表示北纬，默认为0.0。
     * @param string $compatibleflag    容错标志，支持按位操作，默认为0。
     *                                  0x2：图片数据大小错误则报错；
     *                                  0x4：检查图片格式不支持则报错；
     *                                  0x8：上传图片失败则报错；
     *                                  0x20：微博内容长度超过140字则报错；
     *                                  0：以上错误均做容错处理，即发表普通微博；
     *                                  0x2|0x4|0x8|0x20：同旧模式，以上各种情况均报错，不做兼容处理。
     * @return array
     */
    public function addWithPicture( $content, $pic, $clientIp=null, $longitude=null, $latitude=null, $compatibleflag=null) {
        $url = 'https://graph.qq.com/t/add_pic_t';
        $params = array();
        $params['content'] = $content;
        $params['pic'] = '@'.$pic;
        if ( null !== $clientIp )   $params['clientip'] = $clientIp;
        if ( null !== $longitude )  $params['longitude'] = $longitude;
        if ( null !== $latitude )   $params['latitude'] = $latitude;
        if ( null !== $compatibleflag ) $params['compatibleflag'] = $compatibleflag;
        $result = $this->httpPostJSON($url, $params);
        if ( 0 === $result['errcode']*1 ) {
            return $result['data']['id'];
        } else {
            throw new Exception($result['msg']);
        }
    }
    
    /**
     * 获取一条微博的转播或评论信息列表。
     * @param string $flag          标识获取的是转播列表还是点评列表。 
     *                              0：获取转播列表；
     *                              1：获取点评列表；
     *                              2：转播列表和点评列表都获取。
     * @param string $rootid        转发或点评的源微博的ID。
     * @param string $pageflag      分页标识。 0：第一页；1：向下翻页；2：向上翻页。
     * @param string $pagetime      本页起始时间。 第一页：0；向下翻页：上一次请求返回的最后一条记录时间；向上翻页：上一次请求返回的第一条记录的时间。
     * @param string $reqnum        每次请求记录的条数。取值为1-100条。
     * @param string $twitterid     翻页时使用。 第1-100条：0；继续向下翻页：上一次请求返回的最后一条记录id。
     * @return array
     */
    public function getRepostList($flag, $rootid, $pageflag, $pagetime, $reqnum, $twitterid) {
        $url = 'https://graph.qq.com/t/get_repost_list';
        $params = array();
        $params['flag']         = $flag;
        $params['rootid']       = $rootid;
        $params['pageflag']     = $pageflag;
        $params['pagetime']     = $pagetime;
        $params['reqnum']       = $reqnum;
        $params['twitterid']    = $twitterid;
        $result = $this->httpGetJSON($url, $params);
        if ( 0 === $result['errcode']*1 ) {
            return $result['data'];
        } else {
            throw new Exception($result['msg']);
        }
    }
    
    public function getFansList() {}
    public function getIdolList() {}
    public function addIdol() {}
    public function deleteIdol() {}
}