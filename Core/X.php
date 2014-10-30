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
        if ( null === self::$system ) {
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
        if ( null === self::$system ) {
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
    }
    
    /**
     * 运行框架。一般在start之后调用。
     * 如果需要进行其他配置， 则必须在run之前进行。
     * 
     * @return void
     */
    public function run() {
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
     * 该变量保存着所有的成功注册的快捷函数
     *
     * @var array
     */
    protected $shortCutFunctions = array(
        /* 'name'   => $handler, */
    );

    /**
     * 注册一个新的快捷函数到当前框架中去。 如果锁住册的名称已经存在，
     * 则， 旧的快捷函数将会被覆盖。
     * 当注册完成后， 便可以使用下面的方式进行调用：
     * X::system()->shortcut($parm1, $parm2);
     * 其中， shortcut是注册的快捷函数的名称。
     *
     * @param name $name 快捷方法的名称
     * @param callback $handler 快捷方法的处理器
     *
     * @return void
     */
    public function registerShortcutFunction( $name, $handler ) {
        $this->shortCutFunctions[$name] = $handler;
    }
    
    /**
     * PHP的魔法方法， 用于实现虚拟方法的调用， 这里主要用来实现快捷函数的调用。
     *
     * @param string $name 调用的方法名称
     * @param array $parameters 传递给被调用方法的参数列表
     * @return mixed
     */
    public function __call( $name, $parameters ) {
        if ( isset($this->shortCutFunctions[$name]) ) {
            return call_user_func_array($this->shortCutFunctions[$name], $parameters);
        }
        throw new Exception(sprintf('Can not find shoutcut method "%s".', $name));
    }
    
    /**
     * 返回当前框架的版本信息。
     * 
     * @return array
     */
    public function getVersion() {
        return array(1,0,0);
    }
    
    /**
     * 该变量保存框架当前使用的日志记录器， 如果该值为null， 则使用php
     * 内置的error_log函数进行记录日志。
     * 
     * @var InterfaceLogger
     */
    protected $logger = null;
    
    /**
     * 该变量保存框架当前记录日志的界别，默认为LOG_LEVEL_INFO级别。
     * 
     * @var integer
     */
    protected $logLevel = null;
    
    /**
     * 根据给定的消息和跟类进行记录日志。
     * 
     * @param string $message 所要日志的内容
     * @param unknown $category 日志记录的种类
     * @param unknown $level 日志记录的等级，默认为LOG_LEVEL_INFO
     */
    public function log( $message, $category, $level=null ) {
        if ( null === $level ) {
            $level = InterfaceLogger::LOG_LEVEL_INFO;
        }
        
        if ( $this->logLevel > $level ) {
            return;
        }
        
        if ( null === $this->logger ) {
            $message = sprintf('[%s] : %s',$category, $message);
            error_log($message);
        } else {
            $this->getLogger()->log($message, $category);
        }
    }
    
    /**
     * 替换框架当前的log记录器。
     * @return void
     */
    public function setLogger( InterfaceLogger $logger ) {
        $this->logger = $logger;
    }
    
    /**
     * 获取框架当前的log记录器。
     * @return \X\Core\InterfaceLogger
     */
    public function getLogger() {
        return $this->logger;
    }
    
    /**
     * 为框架设计记录日志的级别。低于该级别的日志信息将会被抛弃。
     * @param integer $level
     */
    public function setLogLevel( $level ) {
        $level *= 1;
        if ( 0 === $level ) {
            throw new Exception('0 is not a valid log level, it should be bigger that 0.');
        }
        
        $this->logLevel = $level;
    }
    
    /**
     * 当前框架运行处于的interface名称。
     * @var string
     */
    protected $interfaceType = null;
    
    /**
     * 初始化框架运行的interface环境。
     * @return void
     */
    protected function initInterface() {
        $this->interfaceType = php_sapi_name();
    }
    
    /**
     * 当框架处于CLI模式时的名称
     * @var string
     */
    const PHP_INTERFACE_TYPE_CLI = 'cli';
    
    /**
     * 判断框架是否处于CLI运行模式
     * @return boolean
     */
    public function isCLI() {
        return self::PHP_INTERFACE_TYPE_CLI == $this->interfaceType;
    }
}