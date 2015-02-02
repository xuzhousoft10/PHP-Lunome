<?php
/**
 * The action file for movie/ignore action.
 */
namespace X\Module\Lunome\Action\Movie;

/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for movie/ignore action.
 * @method \X\Module\Lunome\Service\Movie\Service getMovieService()
 * @author Unknown
 */
class Play extends Basic {
    /**
     * 
     */
    public function runAction( $source, $link ) {
        $handler = 'handleSourceFrom'.ucfirst($source);
        echo $this->$handler($link);
    }
    
    /**
     * @param unknown $link
     */
    private function handleSourceFromYouku( $link ) {
        if ( false !== strpos($link, '?') ) {
            $params = substr($link, strpos($link, '?')+1);
            parse_str($params, $params);
            $videoId = $params['url'];
        } else {
            $videoId = $link;
        }
        $videoId = trim($videoId, 'http://v.youku.com/v_show/id_');
        $videoId = trim($videoId, '.html');
        
        $url = "http://player.youku.com/player.php/sid/$videoId/v.swf";
        return $this->getPlayerCode($url);
    }
    
    /**
     * @param unknown $link
     */
    private function handleSourceFromIqiyi( $link ) {
        $link = substr($link, 0, strpos($link, '#'));
        $page = file_get_contents($link);
        preg_match('/data-shareplattrigger-videoid="(.*?)"/', $page, $matches);
        $videoId = $matches[1];
        
        preg_match('/data-player-tvid="(.*?)"/', $page, $matches);
        $tvId = $matches[1];
        
        $urlInfo = parse_url($link);
        $videoCode = trim(trim($urlInfo['path'], '/'), '.html');
        
        $url = "http://player.video.qiyi.com/{$videoId}/0/0/{$videoCode}.swf-albumId={$tvId}-tvId={$tvId}-isPurchase=2-cnId=1";
        return $this->getPlayerCode($url);
    }
    
    /**
     * @param unknown $link
     */
    private function handleSourceFromTudou( $link ) {
        $videoId = trim($link, 'http://www.tudou.com/albumplay/');
        $videoId = trim($videoId, '.html');
        
        $page = file_get_contents($link);
        preg_match('/,iid: ([0-9]*)/', $page, $matches);
        $iid=$matches[1];
        
        $url = "http://www.tudou.com/a/{$videoId}/&bid=05&iid={$iid}/v.swf";
        return $this->getPlayerCode($url, array('wmode'=>'opaque'));
    }
    
    /**
     * @param unknown $link
     * @return string
     */
    private function handleSourceFromSohu( $link ) {
        $page = file_get_contents($link);
        preg_match('/var vid="(.*?)";/', $page, $matches);
        $videoId = $matches[1];
        
        preg_match('/var playlistId="(.*?)";/', $page, $matches);
        $playListId = $matches[1];
        
        $url = "http://share.vrs.sohu.com/{$videoId}/v.swf&topBar=1&autoplay=false&plid={$playListId}";
        return $this->getPlayerCode($url);
    }
    
    /**
     * @param unknown $videoLink
     * @param unknown $extOptions
     * @return string
     */
    private function getPlayerCode($videoLink, $extOptions=array()) {
        $options = array(
            'src'               => $videoLink,
            'type'              => 'application/x-shockwave-flash',
            'quality'           => 'high',
            'width'             => '800',
            'height'            => '450',
            'align'             => 'middle',
            'allowScriptAccess' => 'always',
            'allowFullScreen'   => 'true',
            'mode'              => 'transparent'
        );
        $options = array_merge($options, $extOptions);
        
        foreach ( $options as $key => $value ) {
            $options[$key] = "$key=\"$value\"";
        }
        $options = implode(' ', $options);
        
        
        $playerCode = '<embed '.$options.' ></embed>';
        return $playerCode;
    }
}