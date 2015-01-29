<?php
/**
 * The action file for movie/index action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Module\Lunome\Util\Action\VisualMain;
use X\Module\Lunome\Service\Movie\Service as MovieService;
use X\Library\XData\Validator;

/**
 * The action class for movie/index action.
 * @author Unknown
 */
class Index extends VisualMain { 
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
     * 该方法不做查询处理，只将查询条件传递给前台由其他action处理。
     * 
     * @param integer $mark 标记码。默认为未标记。
     * @param array $query 查询条件。 默认为空。
     * 
     */
    public function runAction( $mark=MovieService::MARK_UNMARKED, $query=null ) {
        $movieService = $this->getMovieService();
        $view = $this->getView();
        $markNames  = $movieService->getMarkNames();
        $moduleConfig = $this->getModule()->getConfiguration();
        
        if ( !Validator::isInteger($mark) && !isset($markNames[$mark]) ) {
            $this->gotoURL('/?module=lunome&action=movie/index', array('mark'=>MovieService::MARK_UNMARKED));
        }
        $mark = intval($mark);
        
        $this->setPageTitle($movieService->getMediaName());
        $this->activeMenuItem(self::MENU_ITEM_MOVIE);
        
        /* Load index view */
        $viewName   = 'MEDIA_INDEX';
        $viewPath   = $this->getParticleViewPath('Movie/Index');
        $view->loadParticle($viewName, $viewPath);
        
        /* add query data to view. */
        $query = (empty($query) || !is_array($query)) ? array() : $query;
        $view->setDataToParticle($viewName, 'query', htmlspecialchars(json_encode($query)));
        
        /* Add mark data to view. */
        $markInfo = array();
        foreach ( $markNames as $key => $markName ) {
            $markInfo[$key]['name']     = $markName;
            $markInfo[$key]['count']    = $movieService->getMarkedCount($key);
            $markInfo[$key]['isActive'] = $mark === $key;
        }
        $view->setDataToParticle($viewName, 'marks', $markInfo);
        
        /* Add mark actions to view. */
        $actions = array();
        switch ( $mark ) {
        case MovieService::MARK_UNMARKED:
            $actions[MovieService::MARK_INTERESTED]    = array('style'=>'success');
            $actions[MovieService::MARK_WATCHED]       = array('style'=>'info');
            $actions[MovieService::MARK_IGNORED]       = array('style'=>'default');
            break;
        case MovieService::MARK_INTERESTED:
            $actions[MovieService::MARK_WATCHED]       = array('style'=>'info');
            $actions[MovieService::MARK_IGNORED]       = array('style'=>'default');
            break;
        case MovieService::MARK_WATCHED:
            $actions[MovieService::MARK_IGNORED]       = array('style'=>'default');
            break;
        case MovieService::MARK_IGNORED:
            $actions[MovieService::MARK_INTERESTED]    = array('style'=>'success');
            $actions[MovieService::MARK_WATCHED]       = array('style'=>'info');
            break;
        default:break;
        }
        foreach ( $actions as $markKey => $markAction ) {
            $actions[$markKey]['name'] = $markNames[$markKey];
        }
        $view->setDataToParticle($viewName, 'markActions', htmlspecialchars(json_encode($actions)));
        
        /* Add current mark to view. */
        $view->setDataToParticle($viewName, 'currentMark', $mark);
        
        /* Add waitting image resource path to view. */
        $view->setDataToParticle($viewName, 'mediaItemWaitingImage', $moduleConfig->get('media_item_operation_waiting_image'));
        $view->setDataToParticle($viewName, 'mediaLoaderLoaddingImage', $moduleConfig->get('media_loader_loading_image'));
        
        /* Add debug mark to view. */
        $isDebug = X::system()->getConfiguration()->get('is_debug') ? 'true' : 'false';
        $view->setDataToParticle($viewName, 'isDebug', $isDebug);
        
        /* Add page size to view. */
        $view->setDataToParticle($viewName, 'pageSize', $moduleConfig->get('media_list_page_size'));
        
        /* Add search condition data to view.  */
        $searchConditionData = array();
        $searchConditionData['regions'] = $movieService->getRegions();
        $searchConditionData['languages'] = $movieService->getLanguages();
        $searchConditionData['categories'] = $movieService->getCategories();
        $view->setDataToParticle($viewName, 'searchData', $searchConditionData);
        
        /* Add tool bar view. */
        $viewName   = 'MEDIA_TOO_BAR';
        $viewPath   = $this->getParticleViewPath('Movie/ToolBar');
        $this->getView()->loadParticle($viewName, $viewPath);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        $assetsURL = X::system()->getConfiguration()->get('assets-base-url');
        $this->getView()->addScriptFile('media-index', $assetsURL.'/js/media_index.js');
        $this->getView()->addScriptFile('cookie', $assetsURL.'/library/jquery/plugin/cookie.js');
    }
}