<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Category;

/**
 * 
 */
use X\Module\Backend\Util\Action\Basic;

/**
 * 
 */
class Delete extends Basic {
    /**
     * 
     */
    public function runAction($id) {
        $movieService = $this->getMovieService();
        $movieService->deleteCategory($id);
        $this->gotoURL('/?module=backend&action=movie/category/index');
    }
}