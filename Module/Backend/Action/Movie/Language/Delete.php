<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Language;

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
        $movieService->deleteLanguage($id);
        $this->gotoURL('/?module=backend&action=movie/language/index');
    }
}