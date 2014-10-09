<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action\Media;

/**
 * Visual action class
 */
abstract class Poster extends Basic {
    /**
     * The action handle for index action.
     * @return void
     */
    public function runAction( $id ) {
        $content = $this->getMediaService()->getPoster($id);
        echo $content;
    }
}