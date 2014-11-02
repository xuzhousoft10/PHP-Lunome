<?php
/**
 * 
 */
namespace X\Core\Service;

/**
 * 
 */
use X\Core\Util\Configuration;

/**
 *
 */
abstract class XService extends \X\Core\Basic {
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
     * 从服务的类名中获取服务名称。
     * @param string $className 要获取服务名称的类名
     * @return string
     */
    private static function getServiceNameFromClassName( $className ) {
        $className = explode('\\', $className);
        array_pop($className);
        return str_replace('Service', '', array_pop($className));
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
        $this->beforeStart();
        $this->afterStart();
    }
    
    /**
     * 该方法将在服务启动前执行， 当你在新建一个服务时， 可以重写该方法以便在服务启动前处理。
     * @return void
     */
    protected function beforeStart(){}
    
    /**
     * 该方法将在服务启动后执行， 当你在新建一个服务时， 可以重写该方法以便在服务启动后处理。
     * @return void
     */
    protected function afterStart(){}
    
    /**
     * 结束服务，该方法由管理器结束， 不建议在其他地方调用该方法。
     *  @return void
     */
    public function stop(){
        $this->beforeStop();
        $this->afterStop();
    }
    
    /**
     * 该方法将在服务结束前执行， 当你在新建一个服务时， 可以重写该方法以便在服务结束前处理。
     * @return void
     */
    protected function beforeStop(){}
    
    /**
     * 该方法将在服务结束后执行， 当你在新建一个服务时， 可以重写该方法以便在服务结束后处理。
     * @return void
     */
    protected function afterStop(){}
    
    /**
     * 获取当前服务下的文件或目录的绝对路径。
     * @return string
     */
    public function getServicePath( $path='' ) {
        $service = new \ReflectionClass(get_class($this));
        $servicePath = dirname($service->getFileName());
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $path = $servicePath.DIRECTORY_SEPARATOR.$path;
        return $path;
    }
    
    /**
     * 该变量保存着当前服务的配置信息。
     * @var \X\Core\Util\Configuration
     */
    private $configuration = null;
    
    /**
     * 获取当前服务的配置信息。
     * @return \X\Core\Util\Configuration
     */
    public function getConfiguration() {
        if ( null === $this->configuration ) {
            $servicePath = $this->getServicePath('Config/Main.php');
            $this->configuration = new Configuration($servicePath);
        }
        return $this->configuration;
    }
}