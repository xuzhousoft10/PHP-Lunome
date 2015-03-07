<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Core\X;
use X\Library\XData\Validator;
use X\Module\Lunome\Util\Action\VisualMain;
use X\Module\Lunome\Service\Movie\Service as MovieService;

/**
 * The action class for movie/index action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Index extends VisualMain { 
    /**
     * 当前标记码
     * @var integer
     */
    private $currentMark = MovieService::MARK_UNMARKED;
    
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
        
        /* Check mark parameter. */
        if ( !Validator::isInteger($mark) || !isset($markNames[$mark]) ) {
            $this->gotoURL('/?module=lunome&action=movie/index', array('mark'=>MovieService::MARK_UNMARKED));
        }
        $mark = intval($mark);
        $this->currentMark = $mark;
        
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
        $this->setPageTitle($movieService->getMediaName());
        $this->activeMenuItem(self::MENU_ITEM_MOVIE);
        
        /* Load index view */
        $viewName = 'MEDIA_INDEX';
        $viewPath   = $this->getParticleViewPath('Movie/Index');
        $particleView = $this->getView()->getParticleViewManager()->load($viewName, $viewPath);
        
        /* add query data to view. */
        $particleView->getDataManager()->set('query', htmlspecialchars(json_encode($query)));
        
        /* Add mark data to view. */
        $markInfo = array();
        foreach ( $markNames as $key => $markName ) {
            $markInfo[$key]['name']     = $markName;
            $markInfo[$key]['count']    = $movieService->getMarkedCount($key);
            $markInfo[$key]['isActive'] = $mark === $key;
        }
        $particleView->getDataManager()->set('marks', $markInfo);
        
        /* Add current mark to view. */
        $particleView->getDataManager()->set('currentMark', $mark);
        
        /* Add waitting image resource path to view. */
        $particleView->getDataManager()->set('mediaItemWaitingImage', $moduleConfig->get('media_item_operation_waiting_image'));
        $particleView->getDataManager()->set('mediaLoaderLoaddingImage', $moduleConfig->get('media_loader_loading_image'));
        
        /* Add debug mark to view. */
        $isDebug = X::system()->getConfiguration()->get('is_debug') ? 'true' : 'false';
        $particleView->getDataManager()->set('isDebug', $isDebug);
        
        /* Add page size to view. */
        $particleView->getDataManager()->set('pageSize', $moduleConfig->get('media_list_page_size'));
        
        /* Add handler url for loadding movies. */
        $dataURL = array('mark'=>$mark);
        if ( MovieService::MARK_WATCHED === $mark ) {
            $dataURL['score'] = true;
        }
        $dataURL = $this->createURL('/?module=lunome&action=movie/find', $dataURL);
        $particleView->getDataManager()->set('dataURL', $dataURL);
        
        /* Add configuration to view. */
        $particleView->getDataManager()->set('searchMaxLength', $moduleConfig->get('movie_search_max_length'));
        $particleView->getDataManager()->set('maxAutoLoadTimeCount', $moduleConfig->get('max_auto_load_time_count'));
        
        /* Add search condition data to view.  */
        $searchConditionData = array();
        $searchConditionData['regions'] = $movieService->getRegions();
        $searchConditionData['languages'] = $movieService->getLanguages();
        $searchConditionData['categories'] = $movieService->getCategories();
        $particleView->getDataManager()->set('searchData', $searchConditionData);
        
        /* Add tool bar view. */
        $viewName   = 'MEDIA_TOO_BAR';
        $viewPath   = $this->getParticleViewPath('Movie/ToolBar');
        $this->getView()->getParticleViewManager()->load($viewName, $viewPath);
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
        
        if ( MovieService::MARK_WATCHED === $this->currentMark ) {
            $this->getView()->getScriptManager()->addFile('rate-it', $assetsURL.'/library/jquery/plugin/rate/rateit.js');
            $this->getView()->getLinkManager()->addCSS('rate-it', $assetsURL.'/library/jquery/plugin/rate/rateit.css');
        }
    }
}