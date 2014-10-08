<?php
/**
 * The visual action of lunome module.
 */
namespace X\Module\Lunome\Util\Action;

/**
 * Visual action class
 */
abstract class VisualMainMediaList extends VisualMain {
    protected function getPageSize() {
        return 20;
    }
    
    protected function getPageItemCount() {
        return 15;
    }
}