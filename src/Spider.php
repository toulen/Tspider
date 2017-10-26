<?php
namespace Tspider;

class Spider extends Tbase
{

    // 开始爬去的URL地址
    public $startUrl = [];

    public $method = 'GET';

    public $itemObj;

    public $requestParams = [];

    public $requestHeaders = [];

    private $_canAgreements = ['https', 'http'];


    public function __construct(array $config = []){
        parent::__construct($config);
    }

    protected function beforeRun(){

        if(!is_array($this->startUrl)){
            TspiderLog::log('URL地址必须是一个数组!', TspiderLog::LOG_ERROR);

            return false;
        }

        return true;
    }

    /**
     * 执行爬虫
     */
    public function run(){
        if(!$this->beforeRun()){
            return false;
        }

        TspiderLog::log('开始执行', TspiderLog::LOG_IN_OUT);

        while(true) {

            if(is_array($this->startUrl) && empty($this->startUrl)){
                break;
            }

            // 执行爬取
            $nowUrl = array_shift($this->startUrl);

            $result = TspiderRequest::curlDo($this->method, $nowUrl, $this->requestParams, $this->requestHeaders);

            // 根据规则执行解析
            $this->itemObj->currentUrl = $this->startUrl;

            $parseResult = $this->itemObj->parse($result);

            $handleResult = $this->itemObj->handle($parseResult);

            if ($handleResult === false) {
                // 不需要继续爬取了,直接结束
                break;
            }

            if($handleResult === true){
                continue;
            }

            if(is_string($handleResult)){
                $this->startUrl[] = $handleResult;
            }

            if(is_array($handleResult)){
                $this->startUrl = array_merge($this->startUrl, $handleResult);
            }
        }

        TspiderLog::log('执行结束', TspiderLog::LOG_IN_OUT);
    }

    private function isUrl($str){
        $strArr = explode('://', $str);

        if(count($strArr) < 2){
            return false;
        }

        $agreement = $strArr[0];

        if(!in_array($agreement, $this->_canAgreements)){
            return false;
        }

        return true;
    }
}