<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Service\XDatabase\Core\SQL\Condition\Builder as ConditionBuilder;
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for movie/ignore action.
 * @method \X\Module\Lunome\Service\Movie\Service getMovieService()
 * @author Unknown
 */
class Find extends Basic {
    /**
     * 
     */
    public function runAction( $mark, $condition, $position, $length ) {
        /* 整理查询条件 */
        $con = ConditionBuilder::build();
        $condition = empty($condition) ? array() : $condition;
        foreach ( $condition as $item => $value ) {
            $operator = $value[0];
            $value = trim(substr($value, 1));
            switch ( $operator ) {
            case '*' : $con->like($item, $value); break;
            case '=' : 
            default  : $con->equals($item, $value); break;
            }
        }
        
        /* @var $service \X\Module\Lunome\Service\Movie\Service */
        $service = $this->getService('Movie');
        $medias = $service->findAll($con, $position, $length);
        
        /* 填充封面信息 */
        foreach ( $medias as $index => $media ) {
            if ( 0 === $media['has_cover']*1 ) {
                $medias[$index]['cover'] = $service->getMediaDefaultCoverURL();
            } else {
                $medias[$index]['cover'] = $service->getMediaCoverURL($media['id']);
            }
        }
        
        /* 返回media列表 */
        echo json_encode($medias);
    }
}