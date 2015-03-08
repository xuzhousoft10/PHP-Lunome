<?php
/**
 * @license LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 */
namespace X\Module\Lunome\Action\Movie;
/**
 * Use statements
 */
use X\Module\Lunome\Util\Action\Basic;

/**
 * The action class for movie/play action.
 * @author Michael Luthor <michaelluthor@163.com>
 */
class Play extends Basic {
    /**
     * @param string $source The website name of view source.
     * @param string $link The link path of view.
     */
    public function runAction( $source, $link ) {
        $videoSourceURL = null;
        
        switch ( strtoupper($source) ) {
        case 'YOUKU' :
            if ( false !== strpos($link, '?') ) {
                $params = substr($link, strpos($link, '?')+1);
                parse_str($params, $params);
                $videoId = $params['url'];
            } else {
                $videoId = $link;
            }
            $videoId = trim($videoId, 'http://v.youku.com/v_show/id_');
            $videoId = trim($videoId, '.html');
            
            $videoSourceURL = "http://player.youku.com/player.php/sid/$videoId/v.swf";
            break;
        case 'IQIYI':
            $link = substr($link, 0, strpos($link, '#'));
            $page = file_get_contents($link);
            preg_match('/data-shareplattrigger-videoid="(.*?)"/', $page, $matches);
            $videoId = $matches[1];
            
            preg_match('/data-player-tvid="(.*?)"/', $page, $matches);
            $tvId = $matches[1];
            
            $urlInfo = parse_url($link);
            $videoCode = trim(trim($urlInfo['path'], '/'), '.html');
            
            $videoSourceURL = "http://player.video.qiyi.com/{$videoId}/0/0/{$videoCode}.swf-albumId={$tvId}-tvId={$tvId}-isPurchase=2-cnId=1";
            break;
        case 'TUDOU':
            $videoId = trim($link, 'http://www.tudou.com/albumplay/');
            $videoId = trim($videoId, '.html');
            
            $page = file_get_contents($link);
            preg_match('/,iid: ([0-9]*)/', $page, $matches);
            $iid=$matches[1];
            
            $videoSourceURL = "http://www.tudou.com/a/{$videoId}/&bid=05&iid={$iid}/v.swf";
            break;
        case 'SOHU':
            $page = file_get_contents($link);
            preg_match('/var vid="(.*?)";/', $page, $matches);
            $videoId = $matches[1];
            
            preg_match('/var playlistId="(.*?)";/', $page, $matches);
            $playListId = $matches[1];
            
            $videoSourceURL = "http://share.vrs.sohu.com/{$videoId}/v.swf&topBar=1&autoplay=false&plid={$playListId}";
            break;
        default:
            $videoSourceURL = null;
            break;
        }
        
        /* generate player. */
        $options = array(
            'src'               => $videoSourceURL,
            'type'              => 'application/x-shockwave-flash',
            'quality'           => 'high',
            'width'             => '800',
            'height'            => '450',
            'align'             => 'middle',
            'allowScriptAccess' => 'always',
            'allowFullScreen'   => 'true',
            'mode'              => 'transparent'
        );
        
        foreach ( $options as $key => $value ) {
            $options[$key] = "$key=\"$value\"";
        }
        $options = implode(' ', $options);
        
        $playerCode = '<embed '.$options.' ></embed>';
        echo $playerCode;
    }
}