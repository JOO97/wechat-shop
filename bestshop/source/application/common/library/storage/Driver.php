<?php

namespace app\common\library\storage;

use think\Exception;

/**
 * 存储模块驱动
 * Class driver
 * @package app\common\library\storage
 */
class Driver
{
    private $config;    // 上传配置
    private $engine;    // 当前存储引擎类

    /**
     * 构造方法
     * Driver constructor.
     * @param $config
     * @throws Exception
     */
    public function __construct($config)
    {
        $this->config = $config;
        // 实例化当前存储引擎
        $this->engine = $this->getEngineClass();
    }

    /**
     * 执行上传
     */
    public function upload()
    {
        return $this->engine->upload();
    }

    /**
     * 获取错误信息
     */
    public function getError()
    {
        return $this->engine->getError();
    }

    /**
     * 获取路径
     */
    public function getFileName()
    {
        return $this->engine->getFileName();
    }

    /**
     * 获取相关信息
     */
    public function getFileInfo()
    {
        return $this->engine->getFileInfo();
    }

    /**
     * 获取当前的存储引擎
     * @return mixed
     * @throws Exception
     */
    private function getEngineClass()
    {
        $engineName = $this->config['default'];
        $classSpace = __NAMESPACE__ . '\\engine\\' . ucfirst($engineName);
        if (!class_exists($classSpace)) {
            throw new Exception('未找到存储引擎类: ' . $engineName);
        }
        $config = isset($this->config['engine'][$engineName]) ? $this->config['engine'][$engineName] : [];
        return new $classSpace($config);
    }

}
