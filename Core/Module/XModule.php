<?php
/**
 * 
 */
namespace X\Core\Module;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\XUtil;
use X\Core\Util\ConfigurationFile;

/**
 * 
 */
abstract class XModule {
    /**
     * @param array $parameters
     */
    abstract public function run($parameters=array());
    
    /**
     * @return string
     */
    public function getName() {
        $className = get_class($this);
        $className = explode('\\', $className);
        return $className[count($className)-2];
    }
    
    /**
     * @param string $path
     * @return string
     */
    public function getPath( $path=null ) {
        return XUtil::getPathRelatedClass($this, $path);
    }
    
    /**
     * @var unknown
     */
    private $configuration = null;
    
    /**
     * @return \X\Core\Util\ConfigurationFile
     */
    public function getConfiguration() {
        if ( null === $this->configuration ) {
            $configPath = $this->getPath('Configuration/Main.php');
            $this->configuration = new ConfigurationFile($configPath);
        }
        return $this->configuration;
    }
    
    /**
     * @return \X\Core\Util\ConfigurationFile
     */
    private function getManagerConfiguration() {
        return X::system()->getModuleManager()->getConfiguration();
    }
    
    /**
     * @param unknown $item
     * @param unknown $value
     */
    private function updateManagerConfiguration( $item, $value ){
        $configuration = $this->getManagerConfiguration();
        $configuration[$this->getName()][$item] = $value;
        $configuration->save();
    }
    
    /**
     * @param unknown $item
     * @param mixed $default
     */
    private function getManagerConfigurationValue($item, $default=null) {
        $configuration = $this->getManagerConfiguration();
        return isset($configuration[$this->getName()][$item]) ? $configuration[$this->getName()][$item] : $default;
    }
    
    /**
     * @return void
     */
    public function setAsDefault(){
        $this->updateManagerConfiguration('default', true);
    }
    
    /**
     * @return void
     */
    public function unsetAsDefault(){
        $this->updateManagerConfiguration('default', false);
    }
    
    
    /**
     * @return boolean
     */
    public function isDefaultModule(){
        return true===$this->getManagerConfigurationValue('default', false);
    }
    
    /**
     * @return void
     */
    public function enable() {
        $this->updateManagerConfiguration('enable', true);
    }
    
    /**
     * @return void
     */
    public function disable(){
        $this->updateManagerConfiguration('enable', false);
    }
    
    /**
     * @return mixed
     */
    public function isEnabled(){
        return $this->getManagerConfigurationValue('enable');
    }
}