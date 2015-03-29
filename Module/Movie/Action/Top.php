<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Movie\Service\Movie\Service AS MovieService;
/**
 * The action class for movie/top action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Top extends Visual {
    /**
     * This action use to display the top list of the movie that user marked.
     * @return void
     */
    protected function runAction() {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        
        $view = $this->getView();
        $topListLimitation = $this->getModule()->getConfiguration()->get('top_list_limitation');
        
        /* Setup view */
        $view->setLayout($this->getLayoutViewPath('BlankThin'));
        $viewName   = 'MEDIA_TOP';
        $path       = $this->getParticleViewPath('Top');
        $this->loadParticle($viewName, $path);
        $view->title = '电影排行榜 -- TOP '.$topListLimitation;
        $this->setDataToParticle($viewName, 'length', $topListLimitation);
        
        $moduleConfig = $this->getModule()->getConfiguration();
        $marks = $moduleConfig->get('movie_mark_names');
        array_shift($marks);
        $list = array();
        foreach ( $marks as $mark => $name ) {
            $list[$mark] = array();
            $list[$mark]['label'] = $marks[$mark];
            $list[$mark]['list'] = $movieService->getTopListByMark($mark, $topListLimitation);
        }
        $this->setDataToParticle($viewName, 'list', $list);
    }
}