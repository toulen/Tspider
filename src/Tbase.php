<?php
namespace Tspider;

class Tbase
{

    public $log = [];

    protected $_config = [];

    public function __construct($config = []){

        $config && $this->setConfig($config);

    }

    /**
     * 设置属性依赖注入
     * @param $config
     */
    protected function setConfig($config){

        $this->_config = $config;

        foreach($config as $key => $item){
            if(property_exists($this, $key)){
                // 有这个属性
                $this->$key = $item;
            }
        }
    }
}