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
        if ( isset($condition['name']) ) {
            $extConditions = explode(';', $condition['name']);
            $fixedExtConditions = array();
            $map = array('导演'=>'director', '演员'=>'actor');
            foreach ( $extConditions as $index => $extCondition ) {
                $extCondition = explode(':', $extCondition);
                if ( isset( $map[$extCondition[0]] ) ) {
                    $fixedExtConditions[$map[$extCondition[0]]] = explode(',', $extCondition[1]);
                    unset($extConditions[$index]);
                }
            }
            $fixedExtConditions['name'] = implode(';', $extConditions);
            $condition = array_merge($condition, $fixedExtConditions);
            if ( empty($condition['name']) ) {
                unset($condition['name']);
            }
        }
        
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
                $medias[$index]['cover'] = $service->getCoverURL($media['id']);
            }
            if ( $score ) {
                $medias[$index]['score'] = $service->getRateScore($media['id']);
            }
        }
        
        /* 返回media列表 */
        echo json_encode(array('count'=>$count, 'medias'=>$medias));
    }
}