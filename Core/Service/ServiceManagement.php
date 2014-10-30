<?php
/**
 * 
 */
namespace X\Core\Service;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\Management;
use X\Core\Util\Configuration;
use X\Core\Exception;

/**
 * 
 */
class ServiceManagement extends Management {
    /**
     * 启动该管理器
     */
    public function start(){
        parent::start();
        $this->configuration = new Configuration(X::system()->getPath('Config/services.php'));
        $this->loadServicesByConfiguration();
    }
    
    /**
     * 结束该管理器
     */
    public function stop() {
        foreach ( $this->services as $name => $service ) {
            if( !$service['enable'] ) {  continue; }
            $service['service']->stop();
        }
        
        parent::stop();
    }
    
    /**
     * 该变量保存着当前管理器的配置信息。
     * @var \X\Core\Util\Configuration
     */
    protected $configuration = null;
    
    /**
     * 获取当前管理器的配置信息
     * @return \X\Core\Util\Configuration
     */
    public function getConfiguration() {
        return $this->configuration;
    }
    
    /**
     * 该变量保存所有配置项目中的服务信息。
     * @var array
     */
    protected $services = array(
    /**
     * 'name' => array(
     *      'enable'    => true, 
     *      'isLoaded'  => true,
     *      'service'   => $service)
     */
    );
    
    /**
     * 根据配置信息加载服务
     * @return void
     */
    protected function loadServicesByConfiguration() {
        $services = $this->configuration;
        foreach ( $services as $name => $configuration ) {
            if ( $configuration['enable'] ) {
                if ( isset($configuration['delay']) && false === $configuration['delay'] ) {
                    $this->load($name, $configuration);
                } else {
                    $this->services[$name]['enable']    = true;
                    $this->services[$name]['isLoaded']  = false;
                    $this->services[$name]['service']   = null;
                }
            } else {
                $this->services[$name]['enable']    = false;
                $this->services[$name]['isLoaded']  = false;
                $this->services[$name]['service']   = null;
            }
        }
    }
    
    /**
     * 将指定名称的服务根据其配置加载到当前管理器中， 并启动。
     * @param string $name 要加在的服务的名称
     * @param array $configuration 要加在的服务的基本配置
     * @return void
     */
    public function load($name, $configuration) {
        $serviceClass = $configuration['class'];
        if ( !class_exists($configuration['class']) ) {
            throw new Exception(sprintf('Can not find service "%s"', $name));
        }
        
        if ( !( is_subclass_of($serviceClass, '\\X\\Core\\Service\\XService') ) ) {
            throw new Exception(sprintf('"%s" is not a available service.'));
        }
        $service = $serviceClass::getService();
        
        $service->start();
        
        $this->services[$name]['enable']    = true;
        $this->services[$name]['isLoaded']  = true;
        $this->services[$name]['service']   = $service;
    }
    
    /**
     * 从当前管理器中获取指定名称的服务。
     * @param string $name 服务名称
     * @return \X\Core\Service\XService
     */
    public function get( $name ) {
        if ( !isset($this->services[$serviceName]) ) {
            throw new Exception(sprintf('Unknown service "%s".', $name));
        } else if ( false === $this->services[$name]['enable'] ) {
            throw new Exception(sprintf('Service "%s" is disabled.', $name));
        }
        
        if ( false === $this->services[$name]['isLoaded'] ) {
            $this->load($name, $this->configuration[$name]);
        }
        return $this->services[$name]['service'];
    }
    
    
    /**
     * Create a new service by given name.
     * 
     * @param string $name The name service.
     * @param string $module The module that the service belongs to, if null then it belongs to system.
     */
    public function create ( $name, $module=null ) {
        if ( $this->has($name) ) {
            throw new Exception(sprintf('Service "%s" already exists.', $name));
        }
        if ( null === $module && !X::system()->getModuleManager()->has($module) ) {
            throw new Exception(sprintf('Module "%s" does not exists.', $name));
        }
        
        /* Generate the namespace of servie */
        $namespace = "X\\Service\\$name";
        if ( null !== $module ) {
            $namespace = "X\\Module\\$module\\Service\\$name";
        }
        
        /* Generate the service file content. */
        $content    = array();
        $content[]  = "<?php";
        $content[]  = "/**\n * This file implements the service Movie\n */";
        $content[]  = "namespace $namespace;";
        $content[]  = "";
        $content[]  = "/**\n * The service class\n */";
        $content[]  = "class Service extends \\X\\Core\\Service\\XService {";
        $content[]  = "}";
        $content = implode("\n", $content);
        
        /* Generate the path of service */
        $path = X::system()->getPath("Service/$name");
        if ( null !== $module ) {
            $path = X::system()->getPath("Module/$module/Service");
            if ( !is_dir($path) ) {
                mkdir($path);
            }
            $path = X::system()->getPath("Module/$module/Service/$name");
        }
        if ( !is_dir($path) ) {
            mkdir($path);
        }
        $path = $path.DIRECTORY_SEPARATOR.'Service.php';
        
        /* Save the service */
        file_put_contents($path, $content);
        
        /* Update the configuration */
        $this->configuration[$name]['enable'] = false;
        $this->configuration[$name]['class'] = "$namespace\\Service";
        $this->configuration->save();
    }
    
    /**
     * Check the service exists by given name.
     * 
     * @param string $name The name of service to check.
     */
    public function has( $name ) {
        return array_key_exists($name, $this->services);
    }
    
    public function getList() {
        return array_keys($this->services);
    }
}