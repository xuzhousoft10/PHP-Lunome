<?php
/**
 * 
 */
namespace X\Core\Service;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\ConfigurationFile;
use X\Core\Util\Exception;
use X\Core\Util\XUtil;

/**
 *
 */
abstract class XService {
    /**
     * 该变量保存着所有已经启动的服务的实例。
     * @var \X\Core\Service\XService[]
     */
    private static $services = array();
    
    /**
     * 获取该服务的实例
     * @return \X\Core\Service\XService
     */
    static public function getService() {
        $className = get_called_class();
        if ( !isset(self::$services[$className]) ) {
            self::$services[$className] = new $className();
        }
    
        return self::$services[$className];
    }
    
    /**
     * @var string
     */
    protected static $serviceName = null;
    
    /**
     * 从服务的类名中获取服务名称。
     * @param string $className 要获取服务名称的类名
     * @return string
     */
    private static function getServiceNameFromClassName( $className ) {
        return static::$serviceName;
    }
    
    /**
     * 静态方法获取服务名称
     * @return string
     */
    public static function getServiceName() {
        return self::getServiceNameFromClassName(get_called_class());
    }
    
    /**
     * 非静态方法获取服务名称
     * @return string
     */
    public function getName() {
        return self::getServiceNameFromClassName(get_class($this));
    }
    
    /**
     * 将构造函数保护起来以禁止从其他地方实例化服务。
     * @return void
     */
    protected function __construct() {}
    
    /**
     * 启动服务，该方法由管理器启动， 不建议在其他地方调用该方法。
     * @return void
     */
    public function start(){
        $this->status = self::STATUS_RUNNING;
    }
    
    /**
     * 结束服务，该方法由管理器结束， 不建议在其他地方调用该方法。
     *  @return void
     */
    public function stop(){ 
        $this->status = self::STATUS_STOPPED;
    }
    
    /**
     * 
     */
    public function destroy() {
        $className = get_called_class();
        unset(self::$services[$className]);
    }
    
    /**
     * 获取当前服务下的文件或目录的绝对路径。
     * @return string
     */
    public function getPath( $path=null ) {
        return XUtil::getPathRelatedClass($this, $path);
    }
    
    /**
     * 该变量保存着当前服务的配置信息。
     * @var \X\Core\Util\ConfigurationFile
     */
    private $configuration = null;
    
    /**
     * 获取当前服务的配置信息。
     * @return \X\Core\Util\ConfigurationFile
     */
    public function getConfiguration() {
        if ( null === $this->configuration ) {
            $this->configuration = new ConfigurationFile($this->getConfigurationFilePath());
        }
        return $this->configuration;
    }
    
    /**
     * @return string
     */
    protected function getConfigurationFilePath() {
        return $this->getPath('Configuration/Main.php');
    }
    
    /**
     * @var integer
     */
    const STATUS_STOPPED = 0;
    
    /**
     * @var integer
     */
    const STATUS_RUNNING = 1;
    
    /**
     * @var status
     */
    private $status = self::STATUS_STOPPED;
    
    /**
     * @return integer
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * @return \X\Core\Util\ConfigurationFile
     */
    private function getManagerConfiguration() {
        return X::system()->getServiceManager()->getConfiguration();
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
    public function enable(){
        $this->checkInstallationRequirement();
        $this->updateManagerConfiguration('enable', true);
    }
    
    /**
     * @return void
     */
    public function disable(){
        $this->checkInstallationRequirement();
        $this->updateManagerConfiguration('enable', false);
    }
    
    /**
     * @return boolean
     */
    public function isEnabled(){
        return $this->getManagerConfigurationValue('enable');
    }
    
    /**
     * @return void
     */
    public function enableLazyLoad(){
        $this->checkInstallationRequirement();
        $this->updateManagerConfiguration('delay', true);
    }
    
    /**
     * @return void
     */
    public function disableLazyLoad(){
        $this->checkInstallationRequirement();
        $this->updateManagerConfiguration('delay', false);
    }
    
    /**
     * @return boolean
     */
    public function isLazyLoadEnabled(){
        return $this->getManagerConfigurationValue('delay', true);
    }
    
    /**
     * @return void
     */
    public function install(){
        $this->updateManagerConfiguration('installed', true);
    }
    
    /**
     * @return void
     */
    public function uninstall(){
        $this->updateManagerConfiguration('installed', false);
    }
    
    /**
     * @return boolean
     */
    public function isInstalled() {
        return $this->getManagerConfigurationValue('installed', false);
    }
    
    /**
     * @throws Exception
     */
    protected function checkInstallationRequirement() {
        if ( !$this->isInstalled() ) {
            throw new Exception('"'.$this->getPettyName().'" has not been installed.');
        }
    }
    
    /**
     * @return string
     */
    public function getPettyName(){
        return $this->getName();
    }
    
    /**
     * @return string
     */
    public function getDescription(){
        return '';
    }
}