<?php
/**
 * Namespave Definition
 */
namespace X\Service\XI18n;

/**
 * Use statements
 */
use X\Core\X;
use X\Library\XUtil\Network;
use X\Service\XI18n\Core\Exception;

/**
 * I18n Service
 * 
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
class XI18nService extends \X\Core\Service\XService {
    /**
     * This value holds the service instace.
     *
     * @var XService
     */
    protected static $service = null;
    
    /**
     * This value contains all the options on the run time of this service.
     * 
     * @var array
     */
    protected $options = array(
        'language'          => 'en_us', // The activated language in the run time.
        'messages'          => array(), // The translate information for messages.
        'acceptLanguages'   => array(), // List of languages that browser accepts
        'paths'             => array(), // List of i18n path
    );
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::afterStart()
     */
    protected function afterStart() {
        X::system()->registerShortcutFunction('t', array($this, 'translate'));
        
        if ( isset($this->configuration['timezone']) ) {
            date_default_timezone_set($this->configuration['timezone']);
        }
        
        if ( 'on' == $this->configuration['language']['detect'] ) {
            $this->detectLanguage();
        } else {
            $this->setLanguage($this->configuration['language']['default']);
        }
    }
    
    /**
     * Detect language from browser request.
     * 
     * @return void
     */
    protected function detectLanguage() {
        if ( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) {
            $this->detectLanguageByHeader();
        } else {
            $this->detectLanguageByIP();
        }
    }
    
    /**
     * Detect user language by http header HTTP_ACCEPT_LANGUAGE
     * 
     * @return void
     */
    protected function detectLanguageByHeader() {
        $languages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $languages = explode(',', $languages);
        
        $acceptLanguages = array();
        foreach ($languages as $language ) {
            $language = explode(';', $language);
            if ( isset($language[1]) ) {
                $language[1] = str_replace('q=', '', $language[1])*1;
            } else {
                $language[1] = 1;
            }
        
            if( 0 == $language[1] ) {
                continue;
            }
            $language[0] = str_replace('-', '_', strtolower($language[0]));
            $acceptLanguages[trim($language[0])] = $language[1];
        }
        
        $languageMap = $this->configuration['language']['map'];
        foreach ( $acceptLanguages as $language => $qValue ) {
            if ( !isset($languageMap[$language]) ) {
                continue;
            }
        
            $qValue = max($acceptLanguages[$language], $qValue);
            $acceptLanguages[$languageMap[$language]] = $qValue;
            unset($acceptLanguages[$language]);
        }
        
        arsort($acceptLanguages, SORT_NUMERIC);
        $this->options['acceptLanguages'] = array_keys($acceptLanguages);
        $this->setLanguage($this->options['acceptLanguages'][0]);
    }
    
    /**
     * Detect language by ip address.
     * 
     * @return void
     */
    protected function detectLanguageByIP() {
        $IPInfo = Network::IpInfo($_SERVER['REMOTE_ADDR']);
        if ( !isset($IPInfo['country']) ) {
            return;
        }
        
        $country = strtoupper($IPInfo['country']);
        if ( !isset($this->configuration['CountryLanguageMap'][$country]) ) {
            return;
        }
        
        $language = $this->configuration['CountryLanguageMap'][$country];
        $this->setLanguage($language);
    }
    
    /**
     * Set active language
     * 
     * @param string $language The name of language, such as 'en_us'
     */
    public function setLanguage( $language ) {
        $this->options['language'] = $language;
        $this->options['messages'] = array();
    }
    
    /**
     * Get activated language name.
     * 
     * @return string
     */
    public function getLanguage() {
        return $this->options['language'];
    }
    
    /**
     * 
     * @return multitype:
     */
    public function getLanguageCodeList() {
        return $this->configuration['LanguageCodeList'];
    }
    
    /**
     * Add i18n folder path into service.
     * 
     * @param string $name The name of i18n path, use to identfy it.
     * @param string $path The path of i18n folder.
     */
    public function addI18nPath( $name, $path ) {
        $this->options['paths'][$name] = $path;
    }
    
    /**
     * Translate message into actived language.
     * 
     * @param string $message Message to be translated.
     * @param array $parms Parameters to translate message.
     * @return string
     */
    public function translate($message, $parms=array()) {
        $this->loadMessages();
        
        $message = $this->options['messages'][$message];
        preg_match_all('/\\{\\$(.*?)\\}/', $message, $messageParameters);
        
        if ( is_null($messageParameters) ) {
            $messageParameters = array(array());
        }
        
        foreach ( $messageParameters[0] as $index => $parameter ) {
            $parmName = $messageParameters[1][$index];
            $message = str_replace($parameter, $parms[$parmName], $message);
        }
        return $message;
    }
    
    /**
     * Load all messages from i18n paths.
     * 
     * @throws Exception
     */
    protected function loadMessages() {
        if ( 0 !== count($this->options['messages']) ) {
            return;
        }
        
        foreach ( $this->options['paths'] as $path ) {
            $tryList = array();
            
            if ( $this->loadMessage($path, $this->options['language']) ) {
                continue;
            }
            $tryList[] = $this->options['language'];
            
            $loadAcceptLanguage = false;
            foreach ( $this->options['acceptLanguages'] as $acceptLanguages ) {
                if ( $this->loadMessage($path, $acceptLanguages) ) {
                    $loadAcceptLanguage = true;
                    break;
                }
                $tryList[] = $acceptLanguages;
            }
            if ( $loadAcceptLanguage ) {
                continue;
            }
            
            if ( $this->loadMessage($path, $this->configuration['language']['default']) ) {
                continue;
            }
            $tryList[] = $this->configuration['language']['default'];
            throw new Exception(sprintf('Can not find language (%s) in "%s".', implode(',', array_unique($tryList)), $path));
        }
    }
    
    /**
     * Load message file from give path.
     * 
     * @param string $path The path to i18n.
     * @param string $language The name of language.
     */
    protected function loadMessage( $path, $language ) {
        $messagePath = sprintf('%s/%s/messages.ini', $path, $language);
        if ( !file_exists($messagePath) ) {
            return false;
        }
        
        $this->options['messages'] = array_merge($this->options['messages'], parse_ini_file($messagePath));
        return true;
    }
}

return __NAMESPACE__;