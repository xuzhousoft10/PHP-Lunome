<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\Visual;
use X\Service\XSession\Service as SessionService;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
use X\Service\XDatabase\Core\ActiveRecord\Criteria;
/**
 * The action class for movie/find action.
 * @author Michael Luthor <michaelluthor@163.com>
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
        X::system()->getServiceManager()->get(SessionService::getServiceName())->close();
        
        /* 格式化查询条件参数。 */
        $mark       = intval($mark);
        $position   = intval($position);
        $length     = 20;
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
        
        /* @var $movieService MovieService */
        $movieService = X::system()->getServiceManager()->get(MovieService::getServiceName());
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
        
        /* Add mark actions to view. */
        $moduleConfig = $this->getModule()->getConfiguration();
        $markNames = $moduleConfig->get('movie_mark_names');
        $actions = array();
        switch ( $mark ) {
        case Movie::MARK_UNMARKED:
            $actions[Movie::MARK_INTERESTED]    = array('style'=>'success');
            $actions[Movie::MARK_WATCHED]       = array('style'=>'info');
            $actions[Movie::MARK_IGNORED]       = array('style'=>'default');
            break;
        case Movie::MARK_INTERESTED:
            $actions[Movie::MARK_WATCHED]       = array('style'=>'info');
            $actions[Movie::MARK_IGNORED]       = array('style'=>'default');
            break;
        case Movie::MARK_WATCHED:
            $actions[Movie::MARK_IGNORED]       = array('style'=>'default');
            break;
        case Movie::MARK_IGNORED:
            $actions[Movie::MARK_INTERESTED]    = array('style'=>'success');
            $actions[Movie::MARK_WATCHED]       = array('style'=>'info');
            break;
        default:break;
        }
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
}