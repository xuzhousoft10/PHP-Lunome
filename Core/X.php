<?php
/**
 * 
 */
namespace X\Core;

/**
 * 
 */
use X\Core\Util\XUtil;

/**
 * 
 */
class X {
    /**
     * 该变量保存该框架在运行时的一个实例。
     * 
     * @var X
     */
    protected static $system = null;
    
    /**
     * 根据根目录启动该框架。
     * 
     * @param string $basePath  该项目的根目录路径。
     * @return X
     */
    public static function start( $basePath ){
        if ( is_null(self::$system) ) {
            self::$system = new X($basePath);
        }
        return self::$system;
    }
    
    /**
     * 结束处理并退出。
     *
     * @return void
     */
    public function stop() {
        exit();
    }
    
    /**
     * 获取已经启动的框架实例， 如果框架没有启动， 则会抛出异常。
     *
     * @return \X\Core\X
     */
    public static function system() {
        if ( is_null(self::$system) ) {
            throw new Exception('X has not been started.');
        }
        return self::$system;
    }
    
    /**
     * 该项目的根目录路径。
     *
     * @var string
     */
    protected $root = null;
    
    /**
     * 根据提供的字符串返回适合当前操作系统的路径字符串。 如果提供的路径为空， 则返回该项目的
     * 根目录路径。
     * 路径字符串的目录分割符为'/'。
     * 
     * @param string $path 路径字符串
     * @return string
     */
    public function getPath( $path='' ) {
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $path = empty($path) ? $this->root : $this->root.DIRECTORY_SEPARATOR.$path;
        return $path;
    }
    
    /**
     * 该变量存储着全局的配置信息。 该配置文件存储在Config/mail.php中。
     *
     * @var array
     */
    protected $configuration = array( 'values'=>null, 'isDirty'=>false );
    
    /**
     * 将配置文件加载到当前项目中， 如果已经加载， 则不会重新加载。
     *
     * @return void
    */
    protected function loadConfiguration() {
        if ( null !== $this->configuration['values'] ) {
            return;
        }
        
        $path = $this->getPath('Config/main.php');
        $this->configuration['values'] = require $path;
    }
    
    /**
     * 根据名称获取配置项目值。 名称可以使用"."进行级别分割。
     * 如果项目不存在则会返回null。
     * 
     * @param string $name 配置项目名称
     * @return mixed
     */
    public function getConfiguration( $name ) {
        $this->loadConfiguration();
        $items = explode('.', $name);
        $item = $this->configuration['values'];
        while ( 0 < count($items) ) {
            $itemName = array_shift($items);
            if ( !isset($item[$itemName]) ) {
                $item = null;
                break;
            } else {
                $item = $item[$itemName];
            }
        }
        return $item;
    }
    
    /**
     * 获取当前的所有配置信息。
     * 
     * @return array
     */
    public function getConfigurations() {
        return $this->configuration['values'];
    }
    
    /**
     * 根据名称设置配置项目的值，名称可以使用"."进行级别分割。
     * 如果项目不存在则会新建该项目。
     * 
     * @param string $name 配置项目名称
     * @param string $value 配置项目值
     * @return void
     */
    public function setConfiguration( $name, $value ) {
        $this->loadConfiguration();
        $items = explode('.', $name);
        $item = &$this->configuration['values'];
    
        while ( 0 < count($items) ) {
            $item = &$item[array_shift($items)];
        }
        $item = $value;
        $this->configuration['isDirty'] = true;
    }
    
    /**
     * 使用数组替换当前配置信息。 这将完全覆盖原先的配置信息。
     * 
     * @param array $config
     */
    public function setConfigurations( $config ) {
        $this->loadConfiguration();
        $this->configuration['values'] = $config;
        $this->configuration['isDirty'] = true;
    }
    
    /**
     * 保存配置信息。如果没有更改则不会进行保存。
     * 
     * @return void
     */
    public function saveConfiguration() {
        if ( !$this->configuration['isDirty'] ) {
            return;
        }
        
        $path = $this->getPath('Config/main.php');
        $data = $this->configuration['values'];
        XUtil::storeArrayToPHPFile($path, $data);
    }
    
    /**
     * 该值保存由终端或者HTTP请求传入的参数列表。
     *
     * @var array
     */
    protected $parameters = array();
    
    /**
     * 加载参数列表到当前框架中。
     *
     * @return void
    */
    protected function loadParameters() {
        if ( $this->isCLI() ) {
            global $argv;
            $parameters = array();
            foreach ( $argv as $index => $parm ) {
                if ( '--' !== substr($parm, 0, 2) ) {
                    continue;
                }
        
                list( $name, $value ) = explode('=', $parm);
                $name = substr($name, 2);
                $parameters[trim($name)] = trim($name);
            }
            $this->parameters = $parameters;
        } else {
            $this->parameters = array_merge($_GET, $_POST, $_REQUEST);
        }
    }
    
    /**
     * 通过参数名称获取传输该框架的参数值。
     * 
     * @param string $name 参数名称
     * @return mixed
     */
    public function getParameter( $name ) {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }
    
    /**
     * 获取传入当前框架的所有参数。
     *
     * @return array
     */
    public function getParameters() {
        return $this->parameters;
    }
    
    /**
     * 通过参数名称更新当前的参数列表。
     * 
     * @param string $name 参数名称
     * @param mixed $value 新的参数值
     */
    public function setParameter( $name, $value ) {
        $this->parameters[$name] = $value;
    }
    
    /**
     * 使用数组替换当前参数列表。
     *
     * @param array $parameters 新的参数列表。
     */
    public function setParameters( $parameters ) {
        $this->parameters = $parameters;
    }
    
    /**
     * 该值保存一个service manager的实例。
     *
     * @var \X\Core\Service\ServiceManagement
     */
    protected $serviceManager = null;
     
    /**
     * 获取当前框架种的service manager的实例。
     *
     * @return \X\Core\Service\ServiceManagement
     */
    public function getServiceManager() {
        return $this->serviceManager;
    }
     
    /**
     * 该值保存着一个module manager的实例。
     *
     * @var \X\Core\Module\ModuleManagement
     */
    protected $moduleManager = null;
    
     
    /**
     * 返回当前框架中的实例。
     *
     * @return \X\Core\Module\ModuleManagement
     */
    public function getModuleManager() {
        return $this->moduleManager;
    }
    
    /**
     * 构造函数， 初始化框架的环境。
     *
     * @param string $root 该项目的根目录路径。
     *
     * @return void
     */
    protected function __construct( $root ) {
        $this->root = $root;
        chdir($this->root);
        $this->initInterface();
        
        register_shutdown_function(array($this, '_shutdown'));
        spl_autoload_register(array($this, '_autoloader'));
        
        $this->serviceManager = \X\Core\Service\ServiceManagement::getManager();
        $this->moduleManager = \X\Core\Module\ModuleManagement::getManager();
        
        $this->getServiceManager()->start();
        $this->getModuleManager()->start();
        $this->loadParameters();
        $this->getModuleManager()->run();
    }
    
    /**
     * 该方法是当脚本结束或异常停止时所调用的方法， 完成该次请求的结尾工作。
     * 该方法由PHP内核调用， 不建议在代码中直接调用该方法。
     * 
     * @return void
     */
    public function _shutdown() {
        $this->serviceManager->stop();
    }
    
    /**
     * 该方法用于实现类的按需加载， 加载方式根据需要加载的类的名称以及其所在的命名空间
     * 进行拼接处理， 所以类的存放位置应当与其命名空间相对应。
     * 该方法由PHP内核调用， 不建议在代码中直接调用该方法。
     *
     * @param string $class 需要动态加载的类的完整名称
     * @return void
     */
    public function _autoloader( $class ) {
        $path = explode('\\', $class);
        $path[0] = $this->root;
        $path[count($path)-1] .= '.php';
        $path = implode(DIRECTORY_SEPARATOR, $path);
        if ( is_file($path) ) {
            require $path;
        }
    }
    
    /**
     * This value holds all the shortcut function calls.
     *
     * @var array
     */
    protected $shortCutFunctions = array(
            // 'name'   => $handler,
    );

    /**
     * Register a new shotcut function into framework.
     *
     * @param name $name The shutcut function's name.
     * @param callback $handler The handler of the shutcut functino.
     *
     * @return void
     */
    public function registerShortcutFunction( $name, $handler ) {
        $this->shortCutFunctions[$name] = $handler;
    }
    
    /**
     * The magic call function to implment the shotcut call.
     *
     * @param string $name The name of shortcut function to call
     * @param array $parameters The parameters to handler.
     * @return mixed
     */
    public function __call( $name, $parameters ) {
        if ( isset($this->shortCutFunctions[$name]) ) {
            return call_user_func_array($this->shortCutFunctions[$name], $parameters);
        }
        throw new Exception(sprintf('Can not find shoutcut method "%s".', $name));
    }
    
    /**
     * A lowercase string that describes the type of interface 
     * (the Server API, SAPI) that PHP is using.
     * 
     * @var string
     */
    protected $interfaceType = null;
    
    /**
     * Initiate the interface that PHP is using.
     * 
     * @return void
     */
    protected function initInterface() {
        $this->interfaceType = php_sapi_name();
    }
    
    /**
     * 
     * @var string
     */
    const PHP_INTERFACE_TYPE_CLI = 'cli';
    
    /**
     * Check if using CLI interface.
     * 
     * @return boolean
     */
    public function isCLI() {
        return self::PHP_INTERFACE_TYPE_CLI == $this->interfaceType;
    }
}