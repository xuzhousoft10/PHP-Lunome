<?php
/**
 * 
 */
namespace X\Core;

/**
 * 
 */
interface InterfaceLogger {
    /**
     * 根据给定的消息和跟类进行记录日志。
     * 
     * @param string $message 所要日志的内容
     * @param unknown $category 日志记录的种类
     */
    public function log($message, $category);
    
    /**
     * 日志级别：输出所有信息。
     * @var integer
     */
    const LOG_LEVEL_VERBOSE     = 1;
    
    /**
     * 日志级别：输出调试级别以及以上信息。
     * @var integer
     */
    const LOG_LEVEL_DEBUG       = 2;
    
    /**
     * 日志级别：输出普通级别以及以上信息。
     * @var integer
     */
    const LOG_LEVEL_INFO        = 3;
    
    /**
     * 日志级别：输出注意级别以及以上信息。
     * @var integer
     */
    const LOG_LEVEL_NOTICE      = 4;
    
    /**
     * 日志级别：输出警告级别以及以上信息。
     * @var integer
     */
    const LOG_LEVEL_WARNING     = 5;
    
    /**
     * 日志级别：输出错误级别以及以上信息。
     * @var integer
     */
    const LOG_LEVEL_ERROR       = 6;
}