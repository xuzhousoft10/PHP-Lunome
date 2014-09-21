<?php
/**
 * Namespace defination
 */
namespace X\Service\XView\Core\Handler;

/**
 * Use statement
 */
use X\Core\X;
use X\Service\XView\XViewService;
use X\Service\XView\Core\Exception;

/**
 * The view handler for html page.
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version Version 0.0.0
 */
class Html extends \X\Service\XView\Core\View {
    /**
     * Safe mode mark
     * 
     * @var boolean
     */
    protected $safeMode = false;
    
    /**
     * Enable safe mode for this view.
     * 
     * @return void
     */
    public function enableSafeMode() {
        $this->safeMode = true;
    }
    
    /**
     * Disable safe mode for this view.
     *
     * @return void
     */
    public function disableSafeMode() {
        $this->safeMode = false;
    }
    
    /* */
    const FORMAT_MODE_NONE = null;
    const FORMAT_MODE_COMPRESS = 'Compress';
    
    /**
     * 
     * @var unknown
     */
    protected $formatMode = self::FORMAT_MODE_NONE;
    
    /**
     * 
     * @param unknown $mode
     */
    public function setFormatMode( $mode ) {
        $this->formatMode = $mode;
    }
    
    /**
     * The title of the page.
     *
     * @var string
     */
    public $title = '';
    
    /**
     * Get the content of title 
     * 
     * @return string
     */
    protected function getTitleContent( ) {
        $title = $this->title;
        if ( $this->safeMode ) {
            $title = htmlspecialchars($title);
        }
        return sprintf('<title>%s</title>', $title);
    }
    
    /**
     * The style list that current page used.
     * 
     * @var array
     */
    protected $styles = array(
    /* 'body@screen' => array(
     *   'item'  => 'body'
     *   'style' => array('background-color'=>'red'),
     *   'media' => 'screen',
     * ),
     */
    );
    
    /**
     * Add style into current page.
     * 
     * @param string $item The name of item.
     * @param array $style The css attributes of the item.
     */
    public function addStyle( $item, array $style, $media=null ) {
        if ( empty($item) || empty($style) ) {
            throw new Exception('$item or $style can not be empty.');
        }
        
        $key = $this->getKeyForStyles($item, $media);
        if ( !isset($this->styles[$key]) ) {
            $this->styles[$key]['style'] = $style;
        } else {
            $this->styles[$key]['style'] = array_merge($this->styles[$key]['style'], $style);
        }
        $this->styles[$key]['item'] = $item;
        $this->styles[$key]['media'] = $media;
    }
    
    /**
     * Set the css attribute value on an exists item.
     * 
     * @param string $item The name of the item.
     * @param string $attribute The name of the attribute.
     * @param mixed $value The value of of the attribute.
     * 
     * @return void
     */
    public function setStyle( $item, $attribute, $value, $media=null ) {
        if ( empty($item) || empty($attribute) || empty($value) ) {
            throw new Exception('$item, $attribute or $value can not be empty.');
        }
        
        $key = $this->getKeyForStyles($item, $media);
        if ( !isset($this->styles[$key]) ) {
            $this->styles[$key] = array();
            $this->styles[$key]['media'] = $media;
        }
        $this->styles[$key]['style'][$attribute] = $value;
    }
    
    /**
     * Get all the definded style information.
     * 
     * @return array
     */
    public function getStyles( ) {
        return $this->styles;
    }
    
    /**
     * Get the value of the attribute on given item.
     * 
     * @param string $item The name of the item.
     * @param string $attribute The name of the attribute.
     * 
     * @return mixed
     */
    public function getStyleValue( $item, $attribute, $media=null) {
        $key = $this->getKeyForStyles($item, $media);
        if ( !isset($this->styles[$key]) ) {
            return null;
        } else if ( !isset($this->styles[$key]['style'][$attribute]) ) {
            return null;
        } else {
            return $this->styles[$key]['style'][$attribute];
        }
    }
    
    /**
     * Remove the style item from current page.
     * 
     * @param string $item The item to remove.
     */
    public function removeStyle( $item, $media=null ) {
        $key = $this->getKeyForStyles($item, $media);
        if ( isset($this->styles[$key]) ) {
            unset($this->styles[$key]);
        }
    }
    
    /**
     * Remove attribute from style list.
     * 
     * @param string $item The name of item.
     * @param string $name The name of attribute.
     */
    public function removeStyleAttribute( $item, $name, $media ) {
        $key = $this->getKeyForStyles($item, $media);
        if ( !isset($this->styles[$key]) ) {
            return;
        }
        
        if ( !isset($this->styles[$key]['style'][$name]) ) {
            return;
        }
        
        unset($this->styles[$key]['style'][$name]);
        if ( 0 === count($this->styles[$key]['style']) ) {
            unset($this->styles[$key]);
        }
    }
    
    /**
     * Get the conetnet of styles.
     * 
     * @return string
     */
    protected function getStyleContent() {
        if ( 0 === count($this->styles) ) {
            return null;
        }
        
        $styleList = array();
        foreach ( $this->styles as $item => $attribute ) {
            $itemStyle = array();
            foreach ( $attribute['style'] as $name => $value ) {
                $itemStyle[] = sprintf('%s:%s', $name, $value);
            }
            $itemStyle = sprintf('%s {%s;}', $attribute['item'], implode(';', $itemStyle));
            if ( !is_null($attribute['media']) ) {
                $itemStyle = sprintf('@media %s{ %s }', $attribute['media'], $itemStyle);
            }
            $styleList[] = $itemStyle;
        }
        $styleList = implode("\n", $styleList);
        $styleList = sprintf("<style type=\"text/css\">\n%s\n</style>", $styleList);
        return $styleList;
    }
    
    /**
     * Get the key for getting data from styles array.
     * 
     * @param string $item
     * @param string $media
     * @return string
     */
    private function getKeyForStyles( $item, $media ) {
        return is_null($media) ? $item : sprintf('%s@%s', $item, $media);
    }
    
    /**
     * The links list in head element.
     *
     * @var array
     */
    protected  $links = array(
    /* 'identifier' => array(
     *      'rel'=>'stylesheet',
     *      'href'=>'style.css',
     *      'type'=>'text/css',
     *      'hreflang'=>'en',
     *      'media'=>'print',
     *      'sizes'=>'16x16'
     * ),
     */
    );
    
    /* Values for attribute rel in link */
    const LINK_REL_ALTERNATE    = 'alternate';
    const LINK_REL_AUTHOR       = 'author';
    const LINK_REL_HELP         = 'help';
    const LINK_REL_ICON         = 'icon';
    const LINK_REL_LICENCE      = 'licence';
    const LINK_REL_NEXT         = 'next';
    const LINK_REL_PINGBACK     = 'pingback';
    const LINK_REL_PREFETCH     = 'prefetch';
    const LINK_REL_PREV         = 'prev';
    const LINK_REL_SEARCH       = 'search';
    const LINK_REL_SIDEBAR      = 'sidebar';
    const LINK_REL_STYLESHEET   = 'stylesheet';
    const LINK_REL_TAG          = 'tag';
    
    
    /**
     * Add link to this page.
     * 
     * @param string $identifier The name of the link, use to idenity the link.
     * @param string $rel The rel value of the link
     * @param string $type The type value of the link
     * @param string $href The href value of the link
     * @param string $media The media value of the link
     * @param string $hreflang The hreflang value of the link
     * @param string $sizes The sizes value of the link
     */
    public function addLink( $identifier, $rel=null, $type=null, $href=null, $media=null, $hreflang=null, $sizes=null ) {
        if ( is_null($rel) && is_null($href) && is_null($type) && is_null($hreflang) && is_null($sizes) ) {
            throw new Exception('The given parameters can not create a valid link label.');
        }
            
        $attributes = array(
            'rel'       => $rel,
            'href'      => $href,
            'type'      => $type,
            'hreflang'  => $hreflang,
            'media'     => $media,
            'sizes'     => $sizes
        );
        
        if ( !isset($this->links[$identifier]) ) {
            $this->links[$identifier] = $attributes;
        } else {
            $this->links[$identifier] = array_merge($this->links[$identifier],$attributes);
        }
    }
    
    /**
     * Add css link to current page.
     * 
     * @param string $identifier The name of the css
     * @param string $link The link address of the css.
     */
    public function addCssLink( $identifier, $link ) {
        $this->addLink($identifier, self::LINK_REL_STYLESHEET, 'text/css', $link);
    }
    
    /**
     * Set the favicon for current page by given path.
     * 
     * @param string $path The page where the icon stored.
     * @return void
     */
    public function setFavicon( $path='/favicon.ico' ) {
        $this->addLink('favicon', self::LINK_REL_ICON, 'image/x-icon', $path);
    }
    
    /**
     * Get the link information by name
     * 
     * @param string $name The name of the link
     * @return array
     */
    public function getLink( $identifier ) {
        if ( isset($this->links[$identifier]) ) {
            return $this->links[$identifier];
        } else {
            return null;
        }
    }
    
    /**
     * Get all the names of link of current page.
     * 
     * @return array
     */
    public function getLinks() {
        return array_keys($this->links);
    }
    
    /**
     * Get the content of links
     * 
     * @return string
     */
    protected function getLinkContent() {
        $linkList = array();
        foreach ( $this->links as $name => $link ) {
            $linkString = array('<link');
            foreach ( $link as $attr => $value ) {
                if ( is_null($value) ) {
                    continue;
                }
                $linkString[] = sprintf('%s="%s"', $attr, $value);
            }
            $linkString[] = '/>';
            $linkList[] = implode(' ', $linkString);
        }
        
        if ( 0 === count($linkList) ) {
            return null;
        }
        
        $linkList = implode("\n", $linkList);
        return $linkList;
    }
    
    /**
     * The metas contains all meta data of the page.
     *
     * @var array
     */
    protected $metas = array(
    /* 'identifier'=> array(
     *      'name'=>'keywords',
     *      'content'=>'KeyWordList',
     *      'charset'=>'utf-8',
     *      'http-equiv'=>'refresh',
     * ),
     */
    );

    /* Values for attribute name of meta */
    const META_NAME_AUTHOR      = 'author';
    const META_NAME_DESCRIPTION = 'description';
    const META_NAME_KEYWORDS    = 'keywords';
    const META_NAME_GENERATOR   = 'generator';
    const META_NAME_REVISED     = 'revised';
    const META_NAME_OTHERS      = 'others';

    /* Values for attribute http-equiv of meta */
    const META_HTTPEQUIV_CONTENT_TYPE   = 'content-type';
    const META_HTTPEQUIV_EXPIRES        = 'expires';
    const META_HTTPEQUIV_REFRESH        = 'refresh';
    const META_HTTPEQUIV_SET_COOKIE     = 'set-cookie';
    
    /**
     * Add meta data to current page.
     * 
     * @param string $identifier The identifier of the meta data.
     * @param string $name The name of the meta data.
     * @param string $content The value of the meta data.
     * @param string $charset The charset of the meta data.
     * @param string $httpEquiv The http-equiv of the meta data.
     */
    public function addMetaData($identifier, $name=null, $content=null, $charset=null, $httpEquiv=null ) {
        if ( empty($name) && empty($content) && empty($charset) && empty($httpEquiv) ) {
            throw new Exception('The given paramaters can not create a valid meta.');
        }
        
        $this->metas[$identifier] = array(
                'name'          => $name,
                'content'       => $content,
                'charset'       => $charset,
                'http-equiv'    => $httpEquiv,
        );
    }
    
    /**
     * Get meta data by identifier
     * 
     * @param string $identifier The identifier of meta data.
     * @return array|NULL
     */
    public function getMeta( $identifier ) {
        if ( isset($this->metas[$identifier]) ) {
            return $this->metas[$identifier];
        } else {
            return null;
        }
    }
    
    /**
     * Get the attribute value of meta data.
     * 
     * @param string $identifier The identifier of the meta data.
     * @param string $attribute The name of attribute.
     * 
     * @return NULL|array
     */
    public function getMetaAttribute($identifier, $attribute) {
        $meta = $this->getMeta($identifier);
        if ( is_null($meta) || !isset($meta[$attribute])) {
            return null;
        }
        return $meta[$attribute];
    }
    
    /**
     * Set the charset of current page.
     * 
     * @param string $charset The name charset.
     */
    public function setCharset( $charset='UTF-8' ) {
        $content = sprintf('text/html; charset=%s', $charset);
        $this->addMetaData('page.charset', null, $content, null, self::META_HTTPEQUIV_CONTENT_TYPE);
    }
    
    /**
     * Add keyword to current page.
     * 
     * @param string $keyword The keyword to add.
     * @param string $_ ...
     */
    public function addKeyword( $keyword ) {
        $this->addKeywords(func_get_args());
    }
    
    /**
     * 
     * @param unknown $keywords
     */
    public function addKeywords( $newKeywords ) {
        $keywords = $this->getMetaAttribute('page.keyword', 'content');
        if ( is_null($keywords) ) {
            $keywords = array();
        } else {
            $keywords = explode(',', $keywords);
        }
        $keywords = array_merge($newKeywords);
        $keywords = array_unique($keywords);
        $keywords = implode(',', $keywords);
        $this->addMetaData('page.keyword', self::META_NAME_KEYWORDS, $keywords );
    }
    
    /**
     * Jump to another page after given seconds.
     * 
     * @param string $url The target url
     * @param integer $seconds The time to stay on this page.
     */
    public function refreshTo( $url, $seconds=0 ) {
        $content = sprintf('%d; URL=%s', $seconds, $url);
        $this->addMetaData('page.refresh', null, $content, null, self::META_HTTPEQUIV_REFRESH);
    }
    
    /**
     * Add author information of current page.
     * 
     * @param string $author The information about author
     */
    public function addAuthor( $author ) {
        $this->addMetaData('page.author', self::META_NAME_AUTHOR, $author);
    }
    
    /**
     * Add description of current page.
     * 
     * @param string $description The description of current page.
     */
    public function addDescription ( $description ) {
        $this->addMetaData('page.description', self::META_NAME_DESCRIPTION, $description);
    }
    
    /**
     * Set expired tiem of current page.
     * 
     * @param int $time The time timestamp
     */
    public function setExpireTime( $time ) {
        $time = gmstrftime('%A %d %B %Y %H:%M GMT', $time);
        $this->addMetaData('page.expires', null, $time, null, self::META_HTTPEQUIV_EXPIRES);
    }
    
    /**
     * Set the frequency that search comes.
     * 
     * @param int $time The number of day to revisi.
     */
    public function setRevisitAfter( $time = 3 ) {
        $time = ( 1 == $time ) ? '1 Day' : sprintf('%s Days', $time);
        $this->addMetaData('page.revisit.after', 'revisit-after', $time);
    }
    
    /**
     * Set copyright for current page.
     * 
     * @param string $copyright
     */
    public function setCopyright( $copyright ) {
        $this->addMetaData('page.copyright', 'Copyright', $copyright);
    }
    
    /* Values for the term of method setRobots() */
    const ROBOT_NONE        = 'none';
    const ROBOT_NO_INDEX    = 'noindex';
    const ROBOT_NO_FOLLOW   = 'nofollow';
    const ROBOT_ALL         = 'all';
    const ROBOT_INDEX       = 'index';
    const ROBOT_FOLLOW      = 'follow';
    
    /**
     * Tell the robots how to handle this page.
     * 
     * @param string|array $term The way how the rebots should handle.
     * @param string $robots The name of robot, lik 'googlebot'
     */
    public function setRobots( $term, $robots='Robots' ) {
        if ( is_array($term) ) {
            $term = implode(',', $term);
        }
        $this->addMetaData('page.robots', $robots, $term);
    }
    
    /* Values of transition for page enter/exit */
    const PAGE_TRAN_BOX_IN                  = 0;
    const PAGE_TRAN_BOX_OUT                 =1;
    const PAGE_TRAN_CIRCLE_IN               = 2;
    const PAGE_TRAN_CIRCLE_OUT              = 3;
    const PAGE_TRAN_ERASE_TO_UP             = 4;
    const PAGE_TRAN_ERASE_TO_BOTTOM         = 5;
    const PAGE_TRAN_ERASE_LEFT              = 6;
    const PAGE_TRAN_ERASE_RIGHT             = 7;
    const PAGE_TRAN_SHADE_VERTICAL          = 8;
    const PAGE_TRAN_SHADE_HORIZONTAL        = 9;
    const PAGE_TRAN_CHESSBOARD_HORIZONTAL   = 10;
    const PAGE_TRAN_CHESSBOARD_VERTICAL     = 11;
    const PAGE_TRAN_DISSOLUTION             = 12;
    const PAGE_TRAN_SPLIT_LR_TO_MIDDLE      = 13;
    const PAGE_TRAN_SPLIT_MIDDLE_TO_LR      = 14;
    const PAGE_TRAN_SPLIT_UD_TO_MIDDLE      = 15;
    const PAGE_TRAN_SPLIT_MIDDLE_TO_UD      = 16;
    const PAGE_TRAN_STAIR_TO_BOTTOM_LEFT    = 17;
    const PAGE_TRAN_STAIR_TO_UP_LEFT        = 18;
    const PAGE_TRAN_STAIR_TO_BOTTOM_RIGHT   = 19;
    const PAGE_TRAN_STAIR_TO_UP_RIGHT       = 20;
    const PAGE_TRAN_HORIZONTAL_RANDOM       = 21;
    const PAGE_TRAN_VERTICAL_RANDOM         = 22;
    const PAGE_TRAN_RANDOEM                 = 23;
    
    /* Values of filter for page enter/exit */
    const PAGE_FILTER_BLEND     = 'blendTrans';
    const PAGE_FILTER_REVEAL    = 'revealTrans';
    
    /**
     * Set the action on page enter
     * 
     * @param string $filter The name of fileter
     * @param string $transition The id of transition
     * @param string $duration The time of transition
     */
    public function setPageEnter( $filter=self::PAGE_FILTER_REVEAL, $transition=self::PAGE_TRAN_BOX_OUT, $duration=2 ) {
        $content = sprintf('%s(Duration=%s,Transition=%s)', $filter, $duration, $transition);
        $this->addMetaData('page.enter', 'Page-Enter', $content);
    }
    
    /**
     * Set the action on page exit
     *
     * @param string $filter The name of fileter
     * @param string $transition The id of transition
     * @param string $duration The time of transition
     */
    public function setPageExit( $filter=self::PAGE_FILTER_REVEAL, $transition=self::PAGE_TRAN_CIRCLE_IN, $duration=2 ) {
        $content = sprintf('%s(Duration=%s,Transition=%s)', $filter, $duration, $transition);
        $this->addMetaData('page.exit', 'Page-Exit', $content);
    }
    
    /**
     * Let client do not get the page form local cache.
     * 
     * @return void
     */
    public function disableTheClientCache() {
        $this->addMetaData('page.pragma.no.cach', null, 'No-cach', null, 'Pragma');
    }
    
    /**
     * Add Open Graph Data
     * @param unknown $identifier
     * @param unknown $property
     * @param unknown $content
     */
    public function addOpenGraphData( $identifier, $property, $content ) {
        $this->metas[$identifier] = array(
            'property'  => $property,
            'content'   => $content
        );
    }
    
    /* Open graph property */
    const OPEN_GRAPH_TITLE          = 'og:title';
    const OPEN_GRAPH_TYPE           = 'og:type';
    const OPEN_GRAPH_URL            = 'og:url';
    const OPEN_GRAPH_IMAGE          = 'og:image';
    const OPEN_GRAPH_SITE_NAME      = 'og:site_name';
    const OPEN_GRAPH_ADMINS         = 'og:admins';
    const OPEN_GRAPH_DESCRIPTION    = 'og:description';
    
    /* Open graph property */
    const OPEN_GRAPH_IDENTITY_TITLE         = 'OpenGraph:Title';
    const OPEN_GRAPH_IDENTITY_TYPE          = 'OpenGraph:Type';
    const OPEN_GRAPH_IDENTITY_URL           = 'OpenGraph:URL';
    const OPEN_GRAPH_IDENTITY_IMAGE         = 'OpenGraph:Image';
    const OPEN_GRAPH_IDENTITY_SITE_NAME     = 'OpenGraph:SiteName';
    const OPEN_GRAPH_IDENTITY_ADMINS        = 'OpenGraph:Admins';
    const OPEN_GRAPH_IDENTITY_DESCRIPTION   = 'OpenGraph:Description';
    
    /**
     * 
     * @param unknown $title
     */
    public function setOGTitle( $title ) {
        $this->addOpenGraphData(self::OPEN_GRAPH_IDENTITY_TITLE, self::OPEN_GRAPH_TITLE, $title);
    }
    
    /*  */
    const OPEN_GRAPH_TYPE_ARTICLE = 'article';
    const OPEN_GRAPH_TYPE_BOOK = 'book';
    const OPEN_GRAPH_TYPE_MOVIE = 'movie';
    
    /**
     * 
     * @param unknown $tyle
     */
    public function setOGType( $tyle ) {
        $this->addOpenGraphData(self::OPEN_GRAPH_IDENTITY_TYPE, self::OPEN_GRAPH_TYPE, $tyle);
    }
    
    /**
     * 
     * @param unknown $url
     */
    public function setOGURL( $url ) {
        $this->addOpenGraphData(self::OPEN_GRAPH_IDENTITY_URL, self::OPEN_GRAPH_URL, $url);
    }
    
    /**
     * 
     * @param unknown $image
     */
    public function setOGImage( $image ) {
        $this->addOpenGraphData(self::OPEN_GRAPH_IDENTITY_IMAGE, self::OPEN_GRAPH_IMAGE, $image);
    }
    
    /**
     * 
     * @param unknown $name
     */
    public function setOGSiteName( $name ) {
        $this->addOpenGraphData(self::OPEN_GRAPH_IDENTITY_SITE_NAME, self::OPEN_GRAPH_SITE_NAME, $name);
    }
    
    /**
     * 
     * @param unknown $admins
     */
    public function setOGAdmins( $admins ) {
        $this->addOpenGraphData(self::OPEN_GRAPH_IDENTITY_ADMINS, self::OPEN_GRAPH_ADMINS, $admins);
    }
    
    /**
     * 
     * @param unknown $description
     */
    public function setOGDescription( $description ) {
        $this->addOpenGraphData(self::OPEN_GRAPH_IDENTITY_DESCRIPTION, self::OPEN_GRAPH_DESCRIPTION, $description);
    }
    
    /**
     * Get the content of meta data.
     * 
     * @return string
     */
    protected function getMetaContent() {
        $metaList = array();
        foreach ( $this->metas as $meta ) {
            $metaInfo = array();
            foreach ( $meta as $attribute => $value ) {
                if ( is_null($value) ) {
                    continue;
                } else {
                    $metaInfo[] = sprintf('%s="%s"', $attribute, $value);
                }
            }
            $metaList[] = sprintf('<meta %s />', implode(' ', $metaInfo));
        }
        
        if ( 0 === count($metaList) ) {
            return null;
        }
        
        $metaList = implode("\n", $metaList);
        return $metaList;
    }
    
    /**
     * This value contains all the script stuff.
     *
     * @var array
     */
    protected $scripts = array(
    /* 'identifier' => array(
     *      'type'=>'text/javascript',
     *      'src'=>'',
     *      'content'=>'',
     *      'defer'=>false,
     *      'charset'=>'UTF-8',
     *      'asyns'=>false,
     * ),
     */
    );
    
    /**
     * Add script to current page.
     * 
     * @param string $identifier The name of script
     * @param string $script The content of script
     * @param string $type The type of script
     */
    public function addScriptString( $identifier, $script, $type='text/javascript' ) {
        if ( empty($script) ) {
            return;
        }
        
        $this->scripts[$identifier] = array(
                'type'      => $type,
                'src'       => null,
                'content'   => $script,
                'defer'     => false,
                'charset'   => null,
                'asyns'     => false,
        );
    }
    
    /**
     * Add script file to current page.
     * 
     * @param string $identifier The name of script
     * @param string $link The link of script
     * @param string $type The type of script
     * @param string $charset The charset of script 
     * @param string $asyns The asyns of script
     */
    public function addScriptFile( $identifier, $link, $type='text/javascript', $charset='UTF-8', $asyns=false ) {
        if ( empty($link) ) {
            return;
        }
        
        $this->scripts[$identifier] = array(
                'type'      => $type,
                'src'       => $link,
                'content'   => null,
                'defer'     => false,
                'charset'   => $charset,
                'asyns'     => $asyns,
        );
    }
    
    /**
     * Get all the scripts of current page.
     * 
     * @return array
     */
    public function getScripts() {
        return array_keys($this->scripts);
    }
    
    /**
     * Check that whether the script exists in this view.
     * 
     * @param string $identifier The script identifier
     * @return boolean
     */
    public function hasScript( $identifier ) {
        return isset($this->scripts[$identifier]);
    }
    
    /**
     * Get script information from this view.
     * 
     * @param string $identifier The script identifier
     * @return array
     */
    public function getScript( $identifier ) {
        if ( !$this->hasScript($identifier) ) {
            throw new Exception(sprintf('Can not find script "%s".', $identifier));
        }
        return $this->scripts[$identifier];
    }
    
    /**
     * Remove Script from current page.
     * 
     * @param string $identifier The name of script
     */
    public function removeScript($identifier) {
        if ( isset($this->scripts[$identifier]) ) {
            unset($this->scripts[$identifier]);
        }
    }
    
    /**
     * Get the content of scripts
     * 
     * @return string
     */
    protected function getScriptContent() {
        $scriptList = array();
        foreach ( $this->scripts as $script ) {
            if ( !empty($script['src']) ) {
                $formater = '<script type="%s" src="%s" charset="%s"></script>';
                $scriptList[] = sprintf($formater, $script['type'], $script['src'], $script['charset']);
            } else if ( !empty($script['content']) ) {
                $formater = "<script type=\"%s\">\n%s\n</script>";
                $scriptList[] = sprintf($formater, $script['type'], $script['content']);
            } else {
                continue;
            }
        }
        
        if ( 0 === count($scriptList) ) {
            return null;
        }
        
        $scriptList = implode("\n", $scriptList);
        return $scriptList;
    }
    
    /**
     * The base of the web page.
     *
     * @var array
     */
    protected $base = array(
            'available'=>false,
            'href'=>'',
            'target'=>''
    );

    /* Values for attribute target of base label */
    const TARGET_BLANK  = '_blank';
    const TARGET_PARENT = '_parent';
    const TARGET_SELF   = '_self';
    const TARGET_TOP    = '_top';
    
    /**
     * Set base information about current page.
     * 
     * @param string $link The base link of current page.
     * @param string $target The value of target attribute.
     */
    public function setBase( $link, $target=self::TARGET_SELF ) {
        $this->base['available'] = true;
        $this->base['href'] = $link;
        $this->base['target'] = $target;
    }
    
    /**
     * Get base information of current page.
     * 
     * @return array
     */
    public function getBase() {
        return $this->base['available'] ? $this->base : null;
    }
    
    /**
     * Remove base information from current page.
     * 
     * @return void
     */
    public function removeBase() {
        $this->base['available'] = false;
    }
    
    /**
     * Get the content of base
     * 
     * @return string
     */
    protected function getBaseContent() {
        if ( $this->base['available'] ) {
            return sprintf('<base href="%s" target="%s" />', $this->base['href'], $this->base['target']);
        } else {
            return null;
        }
    }
    
    /**
     * The text of noscript tip.
     *
     * @var string
     */
    public $noscript  = '';
    
    /**
     * Get the content of no script
     * @return string
     */
    protected function getNoScriptContent() {
        if ( empty($this->noscript) ) {
            return null;
        } else if ( $this->safeMode ) {
            return sprintf('<noscript>%s</noscript>', htmlspecialchars($this->noscript));
        } else {
            return sprintf('<noscript>%s</noscript>', $this->noscript);
        }
    }
    
    /**
     * Get the content of head.
     * 
     * @return string
     */
    protected function getHeadContent() {
        $head = array(
            'title'     => $this->getTitleContent(),
            'styles'    => $this->getStyleContent(),
            'links'     => $this->getLinkContent(),
            'metas'     => $this->getMetaContent(),
            'scripts'   => $this->getScriptContent(),
            'noscript'  => $this->getNoScriptContent(),
        );
        
        foreach ( $head as $item => $content ) {
            if ( is_null($content) ) {
                unset($head[$item]);
            }
        }
        
        return implode("\n", $head);
    }
    
    /**
     * The name of the layout
     * -- view : The path of view file.
     * -- data : The view data
     * -- content : The view content after rendered.
     * 
     * @var array
     */
    protected $layout = array('view'=>null, 'data'=>array(), 'content'=>null);
    
    /* System layout names */
    const LAYOUT_SINGLE_COLUMN              = 'SingleColumn';
    const LAYOUT_SINGLE_COLUMN_FULL_WIDTH   = 'SingleColumnFullWidth';
    const LAYOUT_TWO_COLUMNS                = 'TwoColumns';
    const LAYOUT_TWO_COLUMNS_FULL_WIDTH     = 'TwoColumnsFullWidth';
    const LAYOUT_THREE_COLUMNS              = 'ThreeColumns';
    const LAYOUT_THREE_COLUMNS_FULL_WIDTH   = 'ThreeColumnsFullWidth';
    
    /**
     * Load layout into this view, You can pass the $name a file path to use 
     * custom layout, or a name to use system layout. 
     * The system layout names are defined by const which starts with LAYOUT_.
     *
     * @param string $name The name of the layout
     */
    public function loadLayout( $name ) {
        if ( !is_file($name) ) {
            $viewService = X::system()->getServiceManager()->get(XViewService::getServiceName());
            $name = $viewService->getServicePath('Core/HandlerData/Html/Layout/'.$name.'.php');
        }
        
        if ( !is_file($name) ) {
            throw new Exception(sprintf('Can not find layout "%s".', $name));
        }
        
        $this->layout['view'] = $name;
    }
    
    /**
     * Add data to this view
     * 
     * @param string $name The name of the value
     * @param mixed $value The value to that name
     */
    public function addData( $name, $value ) {
        $this->layout['data'][$name] = $value;
    }
    
    /**
     * Update/Add the date of current view.
     * 
     * @param string $name The name the value.
     * @param mixed $value The value of that name.
     */
    public function setData( $name, $value ) {
        $this->layout['data'][$name] = $value;
    }
    
    /**
     * Remove the data from current view.
     * 
     * @param string $name The name of value to remove.
     */
    public function unsetData( $name ) {
        if ( isset($this->layout['data'][$name]) ) {
            unset($this->layout['data'][$name]);
        }
    }
    
    /**
     * Get data value from current view.
     * 
     * @param string $name The name of the value.
     * 
     * @return NULL|mixed
     */
    public function getData($name) {
        if ( isset($this->layout['data'][$name]) ) {
            return $this->layout['data'][$name];
        } else {
            return null;
        }
    }
    
    /**
     * Display current view.
     *
     * @return null
     */
    public function display() {
        echo $this->toString();
    }
    
    /**
     * Convert this object to string.
     *
     * @return string
     */
    public function toString() {
        return $this->getContent();
    }
    
    /**
     * Convert this object to string.
     * 
     * @return string
     */
    public function __toString() {
        return $this->toString();
    }
    
    /**
     * Get the content of this view.
     * 
     * @return string
     */
    public function getContent() {
        $this->render();
        $this->wrapContentBody();
        $this->attachHeadContent();
        $this->wrapHtmlContent();
        $this->attachDoctypeContent();
        $content = $this->layout['content'];
        $content = $this->formatContent($content);
        return $content;
    }
    
    /**
     * 
     * @param unknown $content
     */
    protected function formatContent( $content ) {
        if ( self::FORMAT_MODE_NONE !== $this->formatMode ) {
            $formatter = 'htmlContentFormatter'.$this->formatMode;
            $content = call_user_func_array(array($this, $formatter), array($content));
        }
        return $content;
    }
    
    /**
     * 
     * @param unknown $content
     * @return unknown
     */
    protected function htmlContentFormatterCompress( $content ) {
        $search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
        $replace = array('>','<','\\1');
        
        if (preg_match("/<html/i",$content) == 1 && preg_match("/<\/html>/i",$content) == 1) {
            $content = preg_replace($search, $replace, $content);
        }
        
        return $content;
    }
    
    /**
     * Add body mark on view content
     * 
     * @return void
     */
    protected function wrapContentBody( ) {
        $content = $this->layout['content'];
        $format = "<body>\n%s\n</body>";
        $content = sprintf($format, $content);
        $this->layout['content'] = $content;
    }
    
    /**
     * Warp the content into html element
     * 
     * @return void
     */
    protected function wrapHtmlContent( ) {
        $format = "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n%s\n</html>";
        $newContent = sprintf($format, $this->layout['content']);
        $this->layout['content'] =  $newContent;
    }
    
    /**
     * Get the content of doctype
     * 
     * @return string
     */
    protected function getDocTypeContent() {
        return '<!DOCTYPE html>';
    }
    
    /**
     * Attach doc type content to current view.
     * 
     * @return void
     */
    protected function attachDoctypeContent( ) {
        $doctype = $this->getDocTypeContent();
        $newContent = $doctype."\n".$this->layout['content'];
        $this->layout['content'] =  $newContent;
    }
    
    /**
     * Attach head content into current view.
     * 
     * @return void
     */
    protected function attachHeadContent( ) {
        $headContent = $this->getHeadContent();
        $headContent = sprintf("<head>\n%s\n</head>", $headContent);
        $newContent = $headContent."\n".$this->layout['content'];
        $this->layout['content'] =  $newContent;
    }
    
    /**
     * Render the content of this view.
     * 
     * @return void
     */
    protected function render() {
        $this->renderParticles();
        $view = $this->layout['view'];
        $data = $this->layout['data'];
        $this->layout['content'] = $this->doRender($view, $data);
    }
    
    /**
     * --key : the path of the view
     * --val : The informations for view
     *      -- val : [data] : The data to particle view
     *      -- val : [option] : The option to particle view.
     *      -- val : [content] : The content of the view.
     * @var array
     */
    protected $particles = array();
    
    /**
     * Load paritcle view into current view.
     * 
     * @param string $name
     * @param string $path
     * @param array $option
     * @param array $data
     */
    public function loadParticle( $name, $path, $option=array(), $data=array() ) {
        $this->particles[$name] = array('view'=>$path, 'data'=>$data, 'option'=>$option, 'content'=>null);
    }
    
    /**
     * Add data to particle.
     * 
     * @param string $view -- The view's name.
     * @param array $data -- The data would add into.
     * @return void
     */
    public function setDataToParticle( $view, $name, $value ) {
        $this->particles[$view]['data'][$name] = $value;
    }
    
    /**
     * Get data from particle view.
     * 
     * @param string $view
     * @param string $name
     * @return mixed
     */
    public function getDataFromParticle($view, $name) {
        if ( !isset($this->particles[$view]) ) {
            throw new Exception(sprintf('Can not find particle view "%s"', $name));
        }
        
        if ( !isset($this->particles[$view]['data'][$name]) ) {
            return null;
        }
        
        return $this->particles[$view]['data'][$name];
    }
    
    /**
     * Remove data from particle view.
     * 
     * @param string $view The name of the view.
     * @param string $name The name of the data.
     */
    public function unsetDataInParticle( $view, $name ) {
        if ( isset($this->particles[$view]['data'][$name]) ) {
            unset($this->particles[$view]['data'][$name]);
        }
    }

    /**
     * Chech that whether the paricle exists.
     * 
     * @param string $name The name of particle view.
     * @return boolean
     */
    public function hasParticle( $name ) {
        return isset($this->particles[$name]);
    }
    
    /**
     * Get the paticle information by given name.
     * 
     * @param string $name The particle name.
     * @throws Exception
     * @return array
     */
    public function getParticle( $name ) {
        if ( !$this->hasParticle($name) ) {
            throw new Exception(sprintf('Can not find particle view "%s"', $name));
        }
        
        return $this->particles[$name];
    }
    
    /**
     * Get all particles of current view.
     * 
     * @return array
     */
    public function getParticles() {
        return array_keys($this->particles);
    }
    
    /**
     * Remove particle view from current view.
     * 
     * @param string $name The name of particle to remove.
     */
    public function removeParticle( $name ) {
        if ( isset($this->particles[$name]) ) {
            unset($this->particles[$name]);
        }
    }
    
    /**
     * Displayt the content of particle.
     * 
     * @param string $name The name of particle
     */
    public function displayParticle( $name ) {
        echo $this->getParticleContent($name);
    }
    
    /**
     * Render particle view.
     * 
     * @param string $name The name of particle.
     * @return boolean
     */
    protected function renderParticle( $name ) {
        if ( !isset($this->particles[$name]) ) {
            return false;
        }
        
        $view = $this->particles[$name]['view'];
        $data = array_merge($this->layout['data'], $this->particles[$name]['data']);
        $this->particles[$name]['content'] = $this->doRender($view, $data);
    }
    
    /**
     * Do render by given information.
     * 
     * @param mixed $view The view to render.
     * @param mixed $data The data that view would used.
     * 
     * @return string
     */
    protected function doRender( $view, $data ) {
        $content = null;
        if ( is_file($view) ) {
            extract($data, EXTR_OVERWRITE);
            ob_start();
            ob_implicit_flush(false);
            require $view;
            $content = ob_get_clean();
        } else if ( is_callable($view) ) {
            $content = call_user_func_array($view, $data);
        } else if ( is_string($view) ) {
            $content = $view;
        } else {
            $content = null;
        }
        return $content;
    }
    
    /**
     * Render particles of this view.
     * 
     * @return void
     */
    protected function renderParticles() {
        foreach ( $this->particles as $name => $particle ) {
            $this->renderParticle($name);
        }
    }
    
    /**
     * Get the content of particle.
     * 
     * @param string $name The name particle name.
     * @return NULL|string
     */
    public function getParticleContent( $name ) {
        if ( false === $this->renderParticle($name) ) {
            throw new Exception(sprintf('Can not find particle view "%s".', $name));
        } else {
            return $this->particles[$name]['content'];
        }
    }
    
    /**
     * Cleanup the content of current view.
     * 
     * @return void
     */
    public function cleanUp() {
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        
        $this->layout['content'] = null;
        foreach ( $this->particles as &$particle ) {
            $particle['content'] = null;
        }
    }
}