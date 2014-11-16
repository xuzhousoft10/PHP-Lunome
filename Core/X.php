<?php
/**
 * 
 */
namespace X\Core;

/**
 * 
 */
require dirname(__FILE__).DIRECTORY_SEPARATOR.'Exception.php';

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
     * 该变量用来标记当前框架是否为第一次启动。
     * @var boolean
     */
    protected static $isFirstStart = null;
    
    /**
     * 根据根目录启动该框架。
     * 
     * @param string $basePath  该项目的根目录路径。
     * @return X
     */
    public static function start( $basePath ){
        if ( null === self::$system ) {
            self::$isFirstStart = ( null === self::$isFirstStart ) ? true : false;
            self::$system = new X($basePath);
        }
        return self::$system;
    }
    
    /**
     * 结束框架的运行。
     * @param string $exit 是否结束执行脚本。
     */
    public function stop( $exit=true ) {
        $exit ? exit() : $this->_shutdown();
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
     * 根目录路径。路径字符串的目录分割符为'/'。
     * 注意，该方法用于生成路径而没有限制生成后的路径必须在框架根目录下，换句话说就是，该方法
     * 生成的路径可以是任何地方。甚至是"/etc/passwd"等敏感路径。
     * 
     * @param string $path 路径字符串。
     * @return string 适合当前操作系统的路径字符串。
     */
    public function getPath( $path='' ) {
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $path = empty($path) ? $this->root : $this->root.DIRECTORY_SEPARATOR.$path;
        return $path;
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
                $parm = explode('=', $parm);
                $name = $parm[0];
                $value = isset($parm[1]) ? trim($parm[1]) : true;
                $name = substr($name, 2);
                $parameters[trim($name)] = $value;
            }
            $this->parameters = $parameters;
        } else {
            $this->parameters = array_merge($_GET, $_POST, $_REQUEST);
        }
    }
    
    /**
     * 通过参数名称获取传输该框架的参数值。 如果参数不存在， 则null会作为返回值返回。
     * 
     * @param string $name 参数名称
     * @return mixed
     */
    public function getParameter( $name ) {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }
    
    /**
     * 获取传入当前框架的所有参数。
     * 当框架处于Web服务器模式时， 参数的来源是所有的GET，POST，和REQUEST传过来的参数。
     * 当框架处于Cli模式运行时， 参数的来源是命令行中以"--"开头的参数。并且其余参数将会忽略。
     * 
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
        if ( null === $this->serviceManager ) {
            $this->serviceManager = \X\Core\Service\ServiceManagement::getManager();
        }
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
        if ( null === $this->moduleManager ) {
            $this->moduleManager = \X\Core\Module\ModuleManagement::getManager();
        }
        return $this->moduleManager;
    }
    
    /**
     * 该变量保存着当前框架的配置信息。
     * @var \X\Core\Util\Configuration
     */
    protected $configuration = null;
    
    /**
     * 获取当前框架的配置信息。
     * @return \X\Core\Util\Configuration
     */
    public function getConfiguration() {
        return $this->configuration;
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
        
        if ( self::$isFirstStart ) {
            register_shutdown_function(array($this, '_shutdown'));
        }
        spl_autoload_register(array($this, '_autoloader'));
        
        $this->configuration = new \X\Core\Util\Configuration($this->getPath('Config/main.php'));
        $this->loadParameters();
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
        $this->getModuleManager()->run();
    }
    
    /**
     * 该方法是当脚本结束或异常停止时所调用的方法， 完成该次请求的结尾工作。
     * 该方法由PHP内核调用， 不建议在代码中直接调用该方法。
     * 
     * @return void
     */
    public function _shutdown() {
        /* 如果框架已经停止， 则不再执行该方法。 当手动结束框架但未退出时， 这种情况就会发生。 */
        if ( null === self::$system ) {
            return;
        }
        
        $this->getServiceManager()->stop();
        $this->getModuleManager()->stop();
        self::$system = null;
        spl_autoload_unregister(array($this, '_autoloader'));
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
    
    /**
     * 该变量用来记录当前是否处于测试状态。
     * @var boolean
     */
    public $isTesting = false;
    
    /**
     * 该变量用来记录当前是否处于调试状态。
     * @var boolean
     */
    public $isDebugging = false;
}