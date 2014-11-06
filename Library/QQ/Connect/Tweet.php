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
        return $this->doRequest('user/get_info');
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
        $params = array();
        $params['content'] = $content;
        if ( null !== $clientIp )   $params['clientip'] = $clientIp;
        if ( null !== $longitude )  $params['longitude'] = $longitude;
        if ( null !== $latitude )   $params['latitude'] = $latitude;
        if ( null !== $compatibleflag ) $params['compatibleflag'] = $compatibleflag;
        return $this->doRequest('t/add_t', $params, false);
    }
    
    /**
     * 根据微博ID删除指定微博。
     * @param string $id 微博消息的ID，用来唯一标识一条微博消息。
     * @return array
     */
    public function delete( $id ) {
        $this->doRequest('t/del_t', array('id'=>$id), false);
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
        $params = array();
        $params['content'] = $content;
        $params['pic'] = '@'.$pic;
        if ( null !== $clientIp )   $params['clientip'] = $clientIp;
        if ( null !== $longitude )  $params['longitude'] = $longitude;
        if ( null !== $latitude )   $params['latitude'] = $latitude;
        if ( null !== $compatibleflag ) $params['compatibleflag'] = $compatibleflag;
        $this->doRequest('t/add_pic_t', $params, false);
    }
    
    /**
     * 获取一条微博的转播或评论信息列表。
     * @param string $rootid        转发或点评的源微博的ID。
     * @param string $flag          标识获取的是转播列表还是点评列表。 
     *                              0：获取转播列表；
     *                              1：获取点评列表；
     *                              2：转播列表和点评列表都获取。
     * @param string $pageflag      分页标识。 0：第一页；1：向下翻页；2：向上翻页。
     * @param string $pagetime      本页起始时间。 第一页：0；向下翻页：上一次请求返回的最后一条记录时间；向上翻页：上一次请求返回的第一条记录的时间。
     * @param string $reqnum        每次请求记录的条数。取值为1-100条。
     * @param string $twitterid     翻页时使用。 第1-100条：0；继续向下翻页：上一次请求返回的最后一条记录id。
     * @return array
     */
    public function getRepostList($rootid, $flag=2, $pageflag=0, $pagetime=0, $reqnum=100, $twitterid=0) {
        $params = array();
        $params['flag']         = $flag;
        $params['rootid']       = $rootid;
        $params['pageflag']     = $pageflag;
        $params['pagetime']     = $pagetime;
        $params['reqnum']       = $reqnum;
        $params['twitterid']    = $twitterid;
        return $this->doRequest('t/get_repost_list', $params);
    }
    
    /**
     * 通过名称获取腾讯微博其他用户详细信息。
     * @param string $name 其他用户的账户名。
     * @return array
     */
    public function getUserInfoByName( $name ) {
        return $this->doRequest('user/get_other_info', array('name'=>$name));
    }
    
    /**
     * 通过OpenId获取腾讯微博其他用户详细信息。
     * @param string $openId 其他用户的openid。
     * @return array
     */
    public function getUserInfoByOpenId( $openId ) {
        return $this->doRequest('user/get_other_info', array('fopenid'=>$openId));
    }
    
    /**
     * 获取登录用户的听众列表。
     * @param number $reqnum        请求获取的听众个数。取值范围为1-30。
     * @param number $startindex    请求获取听众列表的起始位置。 第一页：0；继续向下翻页：reqnum*（page-1）。
     * @param number $mode          获取听众信息的模式，默认值为0。 
     *                              0：旧模式，新添加的听众信息排在前面，最多只能拉取1000个听众的信息。
     *                              1：新模式，可以拉取所有听众的信息，暂时不支持排序。
     * @param number $install       判断获取的是安装应用的听众，还是未安装应用的听众。 
     *                              0：不考虑该参数；
     *                              1：获取已安装应用的听众信息；
     *                              2：获取未安装应用的听众信息。
     * @param number $sex           按性别过滤标识，默认为0。此参数当mode=0时使用，支持排序。 
     *                              1：获取的是男性听众信息；
     *                              2：获取的是女性听众信息；
     *                              0：不进行性别过滤，获取所有听众信息。
     */
    public function getFansList($reqnum=30, $startindex=0, $mode=0, $install=0, $sex=0) {
        $params = array();
        $params['reqnum']       = $reqnum;
        $params['startindex']   = $startindex;
        $params['mode']         = $mode;
        $params['install']      = $install;
        $params['sex']          = $sex;
        return $this->doRequest('relation/get_fanslist', $params);
    }
    
    /**
     * 获取登录用户收听的人的列表。
     * @param number $reqnum        请求获取的好友个数。取值范围为1-30。
     * @param number $startindex    请求获取好友列表的起始位置。 第一页：0；继续向下翻页：reqnum*（page-1）。
     * @param number $mode          获取好友信息的模式，默认值为0。 
     *                              0：旧模式，新添加的好友信息排在前面，最多只能拉取1000个好友的信息。
     *                              1：新模式，可以拉取所有好友的信息，暂时不支持排序。
     * @param number $install       判断获取的是安装应用的好友，还是未安装应用的好友。 
     *                              0：不考虑该参数；
     *                              1：获取已安装应用的好友信息；
     *                              2：获取未安装应用的好友信息。
     */
    public function getIdolList($reqnum=30, $startindex=0, $mode=0, $install=0) {
        $params = array();
        $params['reqnum']       = $reqnum;
        $params['startindex']   = $startindex;
        $params['mode']         = $mode;
        $params['install']      = $install;
        return $this->doRequest('relation/get_idollist', $params);
    }
    
    /**
     * 通过名称收听腾讯微博上的用户。
     * @param array $name 要收听的用户的账户名列表。最多30个。
     */
    public function addIdolByName( $name ) {
        $params = array('name'=>implode(',', $name));
        $this->doRequest('relation/add_idol', $params, false);
    }
    
    /**
     * 通过OpenId收听腾讯微博上的用户。
     * @param string $openIds   要收听的用户的openid列表。最多30个。
     */
    public function addIdolByOpenId( $openIds ) {
        $params = array('name'=>implode('_', $openIds));
        $this->doRequest('relation/add_idol', array('fopenids'=>$openIds), false);
    }
    
    /**
     * 通过名称取消收听腾讯微博上的用户。
     * @param string $name
     * @return void
     */
    public function deleteIdolByName( $name ) {
        $this->doRequest('relation/del_idol', array('name'=>$name), false);
    }
    
    /**
     * 通过OpendID取消收听腾讯微博上的用户。
     * @param string $openId 要取消收听的用户的openid。
     */
    public function deleteIdolByOpenId( $openId ) {
        $this->doRequest('relation/del_idol', array('fopenid'=>$openId), false);
    }
    
    /**
     * 检查请求结果是否出错。并将没有错误的结果返回。
     * @param array $response
     * @throws Exception
     * @return array
     */
    protected function checkResponse( $response ) {
        if ( 0 === $response['errcode']*1 ) {
            return $response['data'];
        } else {
            throw new Exception($response['msg'], $response['errcode']*1);
        }
    }
}