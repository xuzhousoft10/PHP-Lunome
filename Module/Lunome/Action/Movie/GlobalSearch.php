<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Library\Html\Parser;
use X\Module\Lunome\Util\Action\Visual;

/**
 * The action class for movie/ignore action.
 * @method \X\Module\Lunome\Service\Movie\Service getMovieService()
 * @author Unknown
 */
class GlobalSearch extends Visual {
    /**
     * 
     */
    public function runAction( $name ) {
        $movies = array_merge(array(),$this->searchMovieOnYouKu($name));
        $movies = array_merge($movies, $this->searchMovieOnIQiYi($name));
        $movies = array_merge($movies, $this->searchMovieOnTuDou($name));
        $movies = array_merge($movies, $this->searchMovieOnSohu($name));
        
        $name   = 'GLOBAL_SEARCH_RESULT_INDEX';
        $path   = $this->getParticleViewPath('Movie/GlobalSearchResult');
        $option = array();
        $data   = array('movies'=>$movies);
        $this->getView()->loadParticle($name, $path, $option, $data);
        $this->getView()->displayParticle($name);
    }
    
    /**
     * @param unknown $name
     * @return multitype:mixed unknown
     */
    private function searchMovieOnYouKu( $name ) {
        $url = 'http://www.soku.com/search_video/q_'.urlencode($name);
        $html = new Parser($url);
        
        $movies = $html->find('.movie');
        $results = array();
        foreach ( $movies as $movie ) {
            $link = $movie->find('.playarea ');
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
            
            $results[] = array('source'=>'youku','name'=>$name, 'link'=>$link, 'thumb'=>$thumb);
        }
        
        return $results;
    }
    
    /**
     * @param unknown $name
     * @return multitype:multitype:string mixed unknown
     */
    private function searchMovieOnIQiYi( $name ) {
        $url = 'http://so.iqiyi.com/so/q_'.urlencode($name);
        $html = new Parser($url);
        $items = $html->find('.list_item');
        $results = array();
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
        
            $results[] = array('source'=>'iqiyi','name'=>$name, 'link'=>$link, 'thumb'=>$thumb);
        }
        return $results;
    }
    
    /**
     * @param unknown $name
     * @return multitype:multitype:string mixed unknown
     */
    private function searchMovieOnTuDou( $name ) {
        $url = 'http://www.soku.com/t/nisearch/'.urlencode($name);
        $html = new Parser($url);
        $movies = $html->find('.movie');
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
        
            $results[] = array('source'=>'tudou','name'=>$name, 'thumb'=>$thumb, 'link'=>$link);
        }
        return $results;
    }
    
    /**
     * @param unknown $name
     * @return multitype:multitype:mixed unknown
     */
    private function searchMovieOnSohu( $name ) {
        $url = 'http://so.tv.sohu.com/mts?wd='.urlencode($name);
        $html = new Parser($url);
        
        $movies = $html->find('.special');
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
        
            $resules[] = array('source'=>'sohu','name'=>$name, 'link'=>$link, 'thumb'=>$thumb);
        }
        return $resules;
    }
}