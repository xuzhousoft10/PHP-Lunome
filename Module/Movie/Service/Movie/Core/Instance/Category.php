<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Core\Model\MovieCategoryModel;
/**
 * 
 */
class Category {
    /**
     * @var MovieCategoryModel
     */
    private $categoryModel = null;
    
    /**
     * @param MovieCategoryModel $categoryModel
     */
    public function __construct( $categoryModel ) {
        $this->categoryModel = $categoryModel;
    }
    
    /**
     * @param string $name
     */
    public function get($name) {
        return $this->categoryModel->get($name);
    }
}