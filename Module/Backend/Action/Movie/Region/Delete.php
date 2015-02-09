<?php
/**
 * 
 */
namespace X\Module\Backend\Action\Movie\Region;

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
        $movieService->deleteRegion($id);
        $this->gotoURL('/?module=backend&action=movie/region/index');
    }
}