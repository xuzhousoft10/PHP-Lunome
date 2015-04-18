<?php
namespace X\Module\Movie\Util\Action;
/**
 * 
 */
use X\Module\Lunome\Util\Action\Basic;
use X\Module\Movie\Service\Movie\Service as MovieService;
/**
 * 
 */
class MovieAttributeBasicAction extends Basic {
    /**
     * @var \X\Module\Movie\Service\Movie\Core\Instance\Movie
     */
    private $movie = null;
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Movie
     */
    public function getMovie() {
        return $this->movie;
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Core\Instance\Account
     */
    public function getCurrentMovieAccount() {
        return $this->getMovieService()->getCurrentAccount();
    }
    
    /**
     * @return \X\Module\Movie\Service\Movie\Service
     */
    public function getMovieService() {
        /* @var $movieService MovieService */
        $movieService = $this->getService(MovieService::getServiceName());
        return $movieService;
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Module\Lunome\Util\Action\Visual::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        
        $movie = $this->getParameter('movie');
        $movie = $this->getMovieService()->get($movie);
        if ( null === $movie ) {
            $this->throw404();
        }
        
        $this->movie = $movie;
    }
}