<?php
/**
 * 
 */
namespace X\Library\XMarkdown;

require_once 'Library/XMarkdown/MarkdownInterface.php';
require_once 'Library/XMarkdown/Markdown.php';

/**
 * The XMarkdown class extends from \Michelf\Markdown
 * 
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class XMarkdown extends \Michelf\Markdown {
    /**
     *  Main function. Performs some preprocessing on the input text
     *  and pass it through the document gamut.(non-PHPdoc)
     *  
     *  @see \Michelf\Markdown::transform()
     */
    public function transform($text) {
        $text = parent::transform($text);
        return $text;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Michelf\Markdown::doHardBreaks()
     */
    protected function doHardBreaks($text) {
        return preg_replace_callback('/\n/',array($this, '_doHardBreaks_callback'), $text);
    }
    
    /**
     * Initialize the parser and return the result of its transform method.
     * This will work fine for derived classes too.
     * 
     * @param string $text The text to convert into html content.
     * @return string
     */
    public static function defaultTransform($text) {
        $text = parent::defaultTransform($text);
        return $text;
    }
}