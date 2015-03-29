<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Core\X;
use X\Library\XData\Validator;
use X\Module\Lunome\Util\Action\VisualMain;
use X\Module\Movie\Service\Movie\Service as MovieService;
use X\Module\Movie\Service\Movie\Core\Instance\Movie;
/**
 * The action class for movie/index action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Index extends VisualMain {
    /**
     * 当前标记码
     * @var integer
     */
    private $currentMark = Movie::MARK_UNMARKED;
    
    /**
     * @param integer $mark 标记码。默认为未标记。
     * @param array $query 查询条件。 默认为空。
     * 
     */
    public function runAction( $mark=Movie::MARK_UNMARKED, $query=null ) {
        $moduleConfig = $this->getModule()->getConfiguration();
        $markNames = $moduleConfig->get('movie_mark_names');
        $mark = (int)$mark;
        
        /* Check mark parameter. */
        if ( !isset($markNames[$mark]) ) {
            $this->gotoURL('/?module=movie&action=index', array('mark'=>Movie::MARK_UNMARKED));
        }
        
        $this->currentMark = $mark;
        $view = $this->getView();
        
        /* Check query parameter. */
        $query = (empty($query) || !is_array($query)) ? array() : $query;
        $queryTemplate = array('region'=>null, 'category'=>null, 'language'=>null, 'name'=>null);
        $queryTemplate = array_intersect_key($query, $queryTemplate);
        $queryTemplate = array_keys($queryTemplate);
        $queryCopy = array();
        if ( isset($query['name']) ) {
            $queryCopy['name'] = $query['name'];
        }
        foreach ( array('region', 'category', 'language') as $queryItem ) {
            if ( isset($query[$queryItem]) && Validator::isUUIDString($query[$queryItem]) ) {
                $queryCopy[$queryItem] = $query[$queryItem];
            }
        }
        $query = $queryCopy;
        
        /* Setup page. */
        $this->setPageTitle($markNames[$mark].'的电影');
        $this->activeMenuItem(self::MENU_ITEM_MOVIE);
        
        /* Load index view */
        $viewName = 'MEDIA_INDEX';
        $viewPath   = $this->getParticleViewPath('Index');
        $particleView = $this->getView()->getParticleViewManager()->load($viewName, $viewPath);
        $particleView->getDataManager()->set('query', htmlspecialchars(json_encode($query)));
        $particleView->getDataManager()->set('marks', $this->getMarkInformation($markNames));
        $particleView->getDataManager()->set('currentMark', $mark);
        $particleView->getDataManager()->set('searchMaxLength', $moduleConfig->get('movie_search_max_length'));
        $particleView->getDataManager()->set('maxAutoLoadTimeCount', $moduleConfig->get('max_auto_load_time_count'));
        $particleView->getDataManager()->set('mediaItemWaitingImage', $moduleConfig->get('media_item_operation_waiting_image'));
        $particleView->getDataManager()->set('mediaLoaderLoaddingImage', $moduleConfig->get('media_loader_loading_image'));
        
        /* Add debug mark to view. */
        $isDebug = X::system()->getConfiguration()->get('is_debug') ? 'true' : 'false';
        $particleView->getDataManager()->set('isDebug', $isDebug);
        
        /* Add page size to view. */
        $particleView->getDataManager()->set('pageSize', $moduleConfig->get('media_list_page_size'));
        
        /* Add handler url for loadding movies. */
        $dataURL = array('mark'=>$mark);
        if ( Movie::MARK_WATCHED === $mark ) {
            $dataURL['score'] = true;
        }
        $dataURL = $this->createURL('/?module=movie&action=find', $dataURL);
        $particleView->getDataManager()->set('dataURL', $dataURL);
        
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        /* Add search condition data to view.  */
        $searchConditionData = array();
        $searchConditionData['regions']     = $movieService->getRegionManger()->find();
        $searchConditionData['languages']   = $movieService->getLanguageManager()->find();
        $searchConditionData['categories']  = $movieService->getCategoryManager()->find();
        $particleView->getDataManager()->set('searchData', $searchConditionData);
        
        /* Add tool bar view. */
        $viewName   = 'MEDIA_TOO_BAR';
        $viewPath   = $this->getParticleViewPath('ToolBar');
        $this->getView()->getParticleViewManager()->load($viewName, $viewPath);
    }
    
    /**
     * @param MovieService $movieService
     * @param integer $mark
     * @return integer
     */
    private function getMarkInformation( $marks ) {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        $account = $movieService->getCurrentAccount();
        $markInfo = array();
        foreach ( $marks as $mark => $markName ) {
            $markCount = 0;
            if ( Movie::MARK_UNMARKED === $mark ) {
                $markCount = $account->countUnmarked();
            } else {
                $markCount = $account->countMarked($mark);
            }
            
            $markInfo[$mark]['name']     = $markName;
            $markInfo[$mark]['count']    = $markCount;
            $markInfo[$mark]['isActive'] = $this->currentMark === $mark;
        }
        return $markInfo;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Util\Action\Visual::beforeDisplay()
     */
    protected function beforeDisplay() {
        parent::beforeDisplay();
        $assetsURL = $this->getAssetsURL();
        $this->getView()->getScriptManager()->addFile('media-index', $assetsURL.'/js/movie/index.js');
        $this->getView()->getScriptManager()->addFile('cookie', $assetsURL.'/library/jquery/plugin/cookie.js');
        
        if ( Movie::MARK_WATCHED === $this->currentMark ) {
            $this->getView()->getScriptManager()->addFile('rate-it', $assetsURL.'/library/jquery/plugin/rate/rateit.js');
            $this->getView()->getLinkManager()->addCSS('rate-it', $assetsURL.'/library/jquery/plugin/rate/rateit.css');
        }
    }
}