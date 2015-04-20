<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Core\Model\MovieNewsModel;
/**
 * 
 */
class News {
    /**
     * @var MovieNewsModel
     */
    private $newsModel = null;
    
    /**
     * @param MovieNewsModel $newsModel
     */
    public function __construct( $newsModel ) {
        $this->newsModel = $newsModel;
    }
    
    /**
     * @param string $name
     */
    public function get($name) {
        return $this->newsModel->get($name);
    }
}