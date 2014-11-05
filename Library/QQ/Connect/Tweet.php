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
    
    public function add() {}
    public function addWithPicture() {}
    public function delete() {}
    public function getRepostList() {}
    public function getUserInfo() {}
    public function getFansList() {}
    public function getIdolList() {}
    public function addIdol() {}
    public function deleteIdol() {}
}