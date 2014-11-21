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
    public function runAction( $mark=0, $condition=null, $position=0, $length=20 ) {
        /* 整理查询条件 */
        $con = ConditionBuilder::build();
        $condition = empty($condition) ? array() : $condition;
        foreach ( $condition as $item => $value ) {
            $operator = $value[0];
            $value = trim(substr($value, 1));
            switch ( $operator ) {
            case '*' : $con->includes($item, $value); break;
            case '=' : 
            default  : $con->equals($item, $value); break;
            }
        }
        
        /* @var $service \X\Module\Lunome\Service\Movie\Service */
        $service = $this->getService('Movie');
        if ( 0 === $mark*1 ) {
            $medias = $service->getUnmarked($con, $length, $position);
            $count = $service->countUnmarked($con);
        } else {
            $medias = $service->getMarked($mark, $con, $length, $position);
            $count = $service->countMarked($mark, null, 0, $con);
        }
        
        
        /* 填充封面信息 */
        foreach ( $medias as $index => $media ) {
            if ( 0 === $media['has_cover']*1 ) {
                $medias[$index]['cover'] = $service->getMediaDefaultCoverURL();
            } else {
                $medias[$index]['cover'] = $service->getMediaCoverURL($media['id']);
            }
        }
        
        /* 返回media列表 */
        echo json_encode(array('count'=>$count, 'medias'=>$medias));
    }
}