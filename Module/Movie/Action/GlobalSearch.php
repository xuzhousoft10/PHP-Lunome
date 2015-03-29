<?php
namespace X\Module\Movie\Action;
/**
 * 
 */
use X\Library\Html\Parser;
use X\Module\Lunome\Util\Action\Visual;
use X\Service\XSession\Service as SessionService;
/**
 * The action class for movie/globalSearch action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class GlobalSearch extends Visual {
    /**
     * @param unknown $name
     */
    public function runAction( $name ) {
        $this->getService(SessionService::getServiceName())->close();
        
        $movieData = array();
        /* search movies from youku. */
        $url = 'http://www.soku.com/search_video/q_'.urlencode($name);
        $html = new Parser($url);
        $movies = (null===$html) ? array() : $html->find('.movie');
        foreach ( $movies as $movie ) {
            $link = $movie->find('.playarea ');
            if ( empty($link) ) {
                continue;
            }
            $link = $link[0];
            $link = $link->find('.s_btn');
            $link = $link[0];
            $link = $link->getAttribute('href');
            if ( false === strpos($link, 'http://v.youku.com') ) {
                continue;
            }
        
            $thumb = $movie->find('.p_thumb');
            $thumb = $thumb[0];
            $thumb = $thumb->find('img');
            $thumb = $thumb[0];
            $thumb = $thumb->getAttribute('src');
        
            $name = $movie->find('.base_name');
            $name = $name[0];
            $name = str_replace(' ', '', trim($name->text()));
        
            $movieData[] = array('source'=>'youku','name'=>$name, 'link'=>$link, 'thumb'=>$thumb);
        }
        
        /* search movie from IQiYi. */
        $url = 'http://so.iqiyi.com/so/q_'.urlencode($name);
        $html = new Parser($url);
        $items = (null===$html) ? array() : $html->find('.list_item');
        foreach ( $items as $item ) {
            $category = $item->getAttribute('data-widget-searchlist-catageory');
            if ( '电影' !== $category ) {
                continue;
            }
        
            $isAiqiyi = $item->find('.vl-inline');
            if ( empty($isAiqiyi) ) {
                continue;
            }
            $isAiqiyi = $isAiqiyi[0];
            $isAiqiyi = $isAiqiyi->getAttribute('class');
            $isAiqiyi = false !== strpos($isAiqiyi, 'icon_iqiyi_new');
            if ( !$isAiqiyi ) {
                continue;
            }
        
            $thumb = $item->find('img');
            $thumb = $thumb[0];
            $thumb = $thumb->getAttribute('src');
        
            $name = $item->find('.result_title');
            $name = $name[0];
            $name = $name->find('a');
            $name = $name[0];
            $name = str_replace(' ', '', trim($name->text()));
        
            $link = $item->find('.info_play_btn');
            $link = $link[0];
            $link = $link->getAttribute('href');
        
            $movieData[] = array('source'=>'iqiyi','name'=>$name, 'link'=>$link, 'thumb'=>$thumb);
        }
        
        /* search movie from tudou. */
        $url = 'http://www.soku.com/t/nisearch/'.urlencode($name);
        $html = new Parser($url);
        $movies = (null===$html) ? array() : $html->find('.movie');
        $results = array();
        foreach ( $movies as $movie ) {
            $link = $movie->find('.playarea');
            if ( empty($link) ) {
                continue;
            }
            $link = $link[0];
            $link = $link->find('.s_btn');
            $link = $link[0];
            $link = $link->getAttribute('href');
            if ( 0 !== strpos($link, 'http://www.tudou.com') ) {
                continue;
            }
        
            $name = $movie->find('.base_name');
            $name = $name[0];
            $name = str_replace(' ', '', trim($name->text()));
        
            $thumb = $movie->find('.p_thumb');
            $thumb = $thumb[0];
            $thumb = $thumb->find('img');
            $thumb = $thumb[0];
            $thumb = $thumb->getAttribute('src');
        
            $movieData[] = array('source'=>'tudou','name'=>$name, 'thumb'=>$thumb, 'link'=>$link);
        }
        
        /* search movie from sohu. */
        $url = 'http://so.tv.sohu.com/mts?wd='.urlencode($name);
        $html = new Parser($url);
        $movies = (null===$html) ? array() : $html->find('.special');
        $resules = array();
        foreach ( $movies as $movie ) {
            $link = $movie->find('.center');
            if ( empty($link) ) {
                continue;
            }
            $link = $link[0];
            $link = $link->find('.btn-playNow');
            if ( empty($link) ) {
                continue;
            }
            $link = $link[0];
            $link = $link->getAttribute('href');
            if ( false === strpos( $link, 'http://tv.sohu.com/') ) {
                continue;
            }
        
            $thumb = $movie->find('.left');
            $thumb = $thumb[0];
            $thumb = $thumb->find('img');
            $thumb = $thumb[0];
            $thumb = $thumb->getAttribute('src');
        
            $name = $movie->find('.center');
            $name = $name[0];
            $name = $name->find('h2');
            $name = $name[0];
            $name = str_replace(' ', '', trim($name->text()));
        
            $movieData[] = array('source'=>'sohu','name'=>$name, 'link'=>$link, 'thumb'=>$thumb);
        }
        
        /* setup global search view. */
        $name   = 'GLOBAL_SEARCH_RESULT_INDEX';
        $path   = $this->getParticleViewPath('GlobalSearchResult');
        $data   = array('movies'=>$movieData);
        $view = $this->loadParticle($name, $path);
        $view->getDataManager()->merge($data);
        $view->display();
    }
}