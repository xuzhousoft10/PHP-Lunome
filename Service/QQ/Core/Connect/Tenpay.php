<?php
/**
 *
 */
namespace X\Service\QQ\Core\Connect;

/**
 * 
 */
class Tenpay extends ProductionBasic {
    /**
     * 获取财付通用户的收货地址。 一个用户可能设置了多条收货地址信息。
     * 查询的用户必须为财付通用户，否则查询将返回失败。
     * 
     * @param number $offset 查询收货地址的偏移量
     * @param number $limit 查询收货地址的返回限制数（即最多期望返回几个收货地址）。 
     * @return array
     */
    public function getAddr( $offset=0, $limit=5 ) {
        $params = array();
        $params['offset']   = $offset;
        $params['limit']    = $limit;
        $params['ver']      = 1;
        return $this->doRequest('cft_info/get_tenpay_addr', $params, false);
    }
}