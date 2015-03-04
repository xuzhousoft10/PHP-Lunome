<?php
namespace X\Service\XView\Core\Util\HtmlView;
use X\Service\XView\Core\Util\Exception;
/**
 * 
 */
class LinkManager {
    /**
     * The links list in head element.
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
    
    /**
     * Add css link to current page.
     * @param string $identifier The name of the css
     * @param string $link The link address of the css.
     */
    public function addCSS( $identifier, $link ) {
        $this->addLink($identifier, 'stylesheet', 'text/css', $link);
    }
    
    /**
     * Set the favicon for current page by given path.
     * @param string $path The page where the icon stored.
     * @return void
     */
    public function setFavicon( $path='/favicon.ico' ) {
        $this->addLink('favicon', 'icon', 'image/x-icon', $path);
    }
    
    /* Values for attribute rel in link */
    const LINK_REL_ALTERNATE    = 'alternate';
    const LINK_REL_AUTHOR       = 'author';
    const LINK_REL_HELP         = 'help';
    const LINK_REL_LICENCE      = 'licence';
    const LINK_REL_NEXT         = 'next';
    const LINK_REL_PINGBACK     = 'pingback';
    const LINK_REL_PREFETCH     = 'prefetch';
    const LINK_REL_PREV         = 'prev';
    const LINK_REL_SEARCH       = 'search';
    const LINK_REL_SIDEBAR      = 'sidebar';
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
     * @param unknown $identifier
     */
    public function removeCssLink( $identifier ) {
        unset($this->links[$identifier]);
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
}