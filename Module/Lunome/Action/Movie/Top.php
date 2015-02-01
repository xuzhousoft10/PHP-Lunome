<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Visual;
use X\Module\Lunome\Service\Movie\Service as MovieService;

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
        $view = $this->getView();
        $movieService = $this->getMovieService();
        $topListLimitation = $this->getModule()->getConfiguration()->get('top_list_limitation');
        
        /* Setup view */
        $view->loadLayout($this->getLayoutViewPath('BlankThin'));
        $viewName   = 'MEDIA_TOP';
        $path       = $this->getParticleViewPath('Movie/Top');
        $view->loadParticle($viewName, $path);
        $view->title = '电影排行榜 -- TOP '.$topListLimitation;
        $view->setDataToParticle($viewName, 'length', $topListLimitation);
        
        /* Get marks, except unmark. */
        $serviceClass = new \ReflectionClass($movieService);
        $consts = $serviceClass->getConstants();
        foreach ( $consts as $name => $value ) {
            if ( 'MARK_' !== substr($name, 0, 5) || MovieService::MARK_UNMARKED === $value ) {
                unset($consts[$name]);
            }
        }
        $marks = $movieService->getMarkNames();
        $list = array();
        foreach ( $consts as $name => $mark ) {
            $list[$mark] = array();
            $list[$mark]['label'] = $marks[$mark];
            $list[$mark]['list'] = $movieService->getTopList($mark, $topListLimitation);
        }
        $view->setDataToParticle($viewName, 'list', $list);
    }
}