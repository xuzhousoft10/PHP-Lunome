<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Service\XSession\Service as SessionService;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
/**
 * 
 */
class Find extends Visual {
    /**
     * @param integer $mark
     * @param array $condition
     * @param integer $position
     * @param integer $length
     * @param boolean $score
     */
    public function runAction( $mark=0, $condition=null, $position=0, $score=false ) {
        $this->getService(SessionService::getServiceName())->close();
        
        $moduleConfig = $this->getModule()->getConfiguration();
        
        $mark       = (int)$mark;
        $position   = intval($position);
        $length     = $moduleConfig->get('movie_index_page_size');
        $score      = $score ? true : false;
        $condition  = $this->buildCondition($condition);
        
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $movieAccount = $movieService->getCurrentAccount();
        
        $criteria = new Criteria();
        $criteria->condition = $condition;
        $criteria->limit = $length;
        $criteria->position = $position;
        if ( Movie::MARK_UNMARKED === $mark ) {
            $medias = $movieAccount->findUnmarked($criteria);
            $count = $movieAccount->countUnmarked($condition);
        } else {
            $medias = $movieAccount->findMarked($mark, $condition);
            $count = $movieAccount->countMarked($mark);
        }
        
        $markStyles = $moduleConfig->get('movie_mark_styles');
        $actions = array();
        switch ( $mark ) {
        case Movie::MARK_UNMARKED:
            $actions[Movie::MARK_INTERESTED]    = array('style'=>$markStyles[Movie::MARK_INTERESTED]);
            $actions[Movie::MARK_WATCHED]       = array('style'=>$markStyles[Movie::MARK_WATCHED]);
            $actions[Movie::MARK_IGNORED]       = array('style'=>$markStyles[Movie::MARK_IGNORED]);
            break;
        case Movie::MARK_INTERESTED:
            $actions[Movie::MARK_WATCHED]       = array('style'=>$markStyles[Movie::MARK_WATCHED]);
            $actions[Movie::MARK_IGNORED]       = array('style'=>$markStyles[Movie::MARK_IGNORED]);
            break;
        case Movie::MARK_WATCHED:
            $actions[Movie::MARK_IGNORED]       = array('style'=>$markStyles[Movie::MARK_IGNORED]);
            break;
        case Movie::MARK_IGNORED:
            $actions[Movie::MARK_INTERESTED]    = array('style'=>$markStyles[Movie::MARK_INTERESTED]);
            $actions[Movie::MARK_WATCHED]       = array('style'=>$markStyles[Movie::MARK_WATCHED]);
            break;
        default:break;
        }
        $markNames = $moduleConfig->get('movie_mark_names');
        foreach ( $actions as $markKey => $markAction ) {
            $actions[$markKey]['name'] = $markNames[$markKey];
        }
        
        /* Setup view */
        $resultGroupSign = 'item-'.uniqid();
        $view = $this->getView();
        $viewName = 'MOVIE_FIND_RESULT';
        $particleView = $view->getParticleViewManager()->load($viewName, $this->getParticleViewPath('FindResult'));
        
        /* add view data. */
        $particleView->getDataManager()->set('movies', $medias);
        $particleView->getDataManager()->set('marks', $actions);
        $particleView->getDataManager()->set('isWatched', Movie::MARK_WATCHED===$mark);
        $particleView->getDataManager()->set('sign', $resultGroupSign);
        $particleView->getDataManager()->set('movieAccount', $movieAccount);
        
        /* 返回media列表 */
        $jsonResult = array();
        $jsonResult['count'] = $count;
        $jsonResult['mediaCount'] = count($medias);
        $jsonResult['medias'] = $particleView->toString();
        $jsonResult['sign'] = $resultGroupSign;
        echo json_encode($jsonResult);
    }
    
    /**
     * 处理电影列表页面的请求。
     * 如果标记不存在，则视为未标记处理。
     * 目前支持的查询条件如下：
     * region:电影区域ID
     * category:电影类型ID
     * language:电影语言ID
     * name:查询文本
     * 其中， 查询文本格式如下：
     * [condition]:value[;[condition2]:value]
     * 支持的condition有：演员和导演
     * 如果Condition没有指定， 则作为电影名称和简介处理。
     * 
     * @param array $condition
     * @return array
     */
    private function buildCondition( $condition ) {
        if ( empty($condition) || !is_array($condition) ) {
            return array();
        }
        
        if ( isset($condition['name']) && !empty($condition['name']) ) {
            $extConditions = explode(';', $condition['name']);
            $fixedExtConditions = array();
            
            $map = array('导演'=>'director', '演员'=>'actor');
            foreach ( $extConditions as $index => $extCondition ) {
                $extCondition = trim($extCondition);
                if ( empty($extCondition) ) {
                    continue;
                }
                
                $extCondition = explode(':', $extCondition);
                if ( isset($map[$extCondition[0]]) && isset($extCondition[1]) ) {
                    $extCondition[1] = trim($extCondition[1]);
                    if ( !empty($extCondition[1]) ) {
                        $fixedExtConditions[$map[$extCondition[0]]] = explode(',', $extCondition[1]);
                    }
                    unset($extConditions[$index]);
                }
            }
            
            $fixedExtConditions['name'] = implode(';', $extConditions);
            $condition = array_merge($condition, $fixedExtConditions);
        }
        
        if ( isset($condition['name']) ) {
            unset($condition['name']);
        }
        
        return $condition;
    }
}