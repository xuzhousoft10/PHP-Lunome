<?php
namespace X\Module\Movie\Service\Movie\Core\Instance;
/**
 * 
 */
use X\Module\Movie\Service\Movie\Core\Model\MovieLanguageModel;
/**
 * 
 */
class Language {
    /**
     * @var MovieLanguageModel
     */
    private $languageModel = null;
    
    /**
     * @param MovieLanguageModel $languageModel
     */
    public function __construct( $languageModel ) {
        $this->languageModel = $languageModel;
    }
    
    /**
     * @param string $name
     */
    public function get($name) {
        return $this->languageModel->get($name);
    }
}