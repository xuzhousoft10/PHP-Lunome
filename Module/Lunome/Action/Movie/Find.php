<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
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
    public function runAction( $mark=0, $condition=null, $position=0, $length=20, $score=false ) {
        $condition = empty($condition) ? array() : $condition;
        /* @var $service \X\Module\Lunome\Service\Movie\Service */
        $service = $this->getService('Movie');
        if ( 0 === $mark*1 ) {
            $medias = $service->getUnmarked($condition, $length, $position);
            $count = $service->countUnmarked($condition);
        } else {
            $medias = $service->getMarked($mark, $condition, $length, $position);
            $count = $service->countMarked($mark, null, 0, $condition);
        }
        
        /* 填充封面信息 */
        foreach ( $medias as $index => $media ) {
            if ( 0 === $media['has_cover']*1 ) {
                $medias[$index]['cover'] = $service->getMediaDefaultCoverURL();
            } else {
                $medias[$index]['cover'] = $service->getMediaCoverURL($media['id']);
            }
            if ( $score ) {
                $medias[$index]['score'] = $service->getRateScore($media['id']);
            }
        }
        
        /* 返回media列表 */
        echo json_encode(array('count'=>$count, 'medias'=>$medias));
    }
}