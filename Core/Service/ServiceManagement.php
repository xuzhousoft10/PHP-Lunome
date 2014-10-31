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
use X\Core\Util\XUtil;

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
            if( !$service['enable'] || !$service['isLoaded'] ) {  continue; }
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
        if ( !isset($this->services[$name]) ) {
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
     * 根据名称创建一个新的服务, 如果名称已经存在， 则会抛出一个异常。
     * 
     * @param string $name 新服务的名称
     * @param string $module 服务所属的module的名称， 如果为空，则该服务属于框架。
     * @return void
     */
    public function create ( $name, $module=null ) {
        if ( $this->has($name) ) {
            throw new Exception(sprintf('Service "%s" already exists.', $name));
        }
        if ( null !== $module && !X::system()->getModuleManager()->has($module) ) {
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
        $config = array('enable'=>false, 'delay'=>true, 'class'=>"$namespace\\Service");
        $this->configuration->set($name, $config);
        $this->configuration->save();
    }
    
    /**
     * 删除指定名称的服务。
     * @param string $name 服务的名称
     */
    public function delete( $name ) {
        if ( !isset($this->configuration[$name]) ) {
            throw new Exception(sprintf('Service "%s" does not exists.', $name));
        }
        
        /* Check is system service */
        $class = explode('\\', $this->configuration[$name]['class']);
        array_shift($class);
        $isSystem = 'Service' === array_shift($class);
        
        /* Get the path that would be deleted */
        $class = explode('\\', $this->configuration[$name]['class']);
        array_shift($class);
        array_pop($class);
        $classPath = X::system()->getPath(implode('/', $class));
        XUtil::deleteFile($classPath);
        unset($this->configuration[$name]);
        $this->configuration->save();
    }
    
    /**
     * 检查服务是否存在。
     * @param string $name 要检查的服务的名称。
     * @return boolean
     */
    public function has( $name ) {
        return array_key_exists($name, $this->services);
    }
    
    /**
     * 获取所有服务的名称列表。
     * @return array
     */
    public function getList() {
        return array_keys($this->services);
    }
    
    /**
     * 根据名称开启指定服务。
     * @param string $name 要开启的服务名称。
     * @return void
     */
    public function enable( $name ) {
        if ( !isset($this->configuration[$name]) ) {
            throw new Exception(sprintf('Service "%s" does not exists.', $name));
        }
        $this->configuration[$name]['enable'] = true;
        $this->configuration->save();
    }
    
    /**
     * 根据名称关闭指定服务。
     * @param string $name 要开启的服务名称。
     * @return void
     */
    public function disable( $name ) {
        if ( !isset($this->configuration[$name]) ) {
            throw new Exception(sprintf('Service "%s" does not exists.', $name));
        }
        $this->configuration[$name]['enable'] = false;
        $this->configuration->save();
    }
    
    /**
     * 根据名称为指定服务开启延迟启动。
     * @param string $name 要开启延迟启动的服务名称。
     * @return void
     */
    public function enableDelayStart( $name ) {
        if ( !isset($this->configuration[$name]) ) {
            throw new Exception(sprintf('Service "%s" does not exists.', $name));
        }
        $this->configuration[$name]['delay'] = true;
        $this->configuration->save();
    }
    
    /**
     * 根据名称为指定服务关闭延迟启动。
     * @param string $name 要关闭延迟启动的服务名称。
     * @return void
     */
    public function disableDelayStart( $name ) {
        if ( !isset($this->configuration[$name]) ) {
            throw new Exception(sprintf('Service "%s" does not exists.', $name));
        }
        $this->configuration[$name]['delay'] = false;
        $this->configuration->save();
    }
}