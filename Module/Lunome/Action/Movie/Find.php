<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Service\Movie\Service as MovieService;
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for movie/find action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Find extends Basic {
    /**
     * @param integer $mark
     * @param array $condition
     * @param integer $position
     * @param integer $length
     * @param boolean $score
     */
    public function runAction( $mark=0, $condition=null, $position=0, $length=20, $score=false ) {
        /* 格式化查询条件参数。 */
        $mark       = intval($mark);
        $position   = intval($position);
        $length     = intval($length);
        $score      = $score ? true : false;
        $condition  = (empty($condition) || !is_array($condition))? array() : $condition;
        if ( isset($condition['name']) ) {
            $extConditions = explode(';', $condition['name']);
            $fixedExtConditions = array();
            $map = array('导演'=>'director', '演员'=>'actor');
            foreach ( $extConditions as $index => $extCondition ) {
                $extCondition = explode(':', $extCondition);
                if ( isset($map[$extCondition[0]]) && isset($extCondition[1]) ) {
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
        
        /* get movie data by condition. */
        $movieService = $this->getMovieService();
        if ( MovieService::MARK_UNMARKED === $mark ) {
            $medias = $movieService->getUnmarked($condition, $length, $position);
            $count = $movieService->countUnmarked($condition);
        } else {
            $medias = $movieService->getMarked($mark, $condition, $length, $position);
            $count = $movieService->countMarked($mark, null, 0, $condition);
        }
        
        /* fill extension information for each movie. */
        foreach ( $medias as $index => $media ) {
            if ( 0 === $media['has_cover']*1 ) {
                $medias[$index]['cover'] = $movieService->getMediaDefaultCoverURL();
            } else {
                $medias[$index]['cover'] = $movieService->getCoverURL($media['id']);
            }
            if ( $score ) {
                $medias[$index]['score'] = $movieService->getRateScore($media['id']);
            }
        }
        
        /* 返回media列表 */
        echo json_encode(array('count'=>$count, 'medias'=>$medias));
    }
}