<?php
namespace Tspider;

use Zend\Dom\Query;

class TspiderItem extends Tbase
{

    public $pageTitle;

    public $currentUrl;

    public $responseStatus = [];

    public $resultIsHtml = false;

    public $responseHeaders = [];

    /**
     * 规则
     * @return array
     */
    public function rules(){}

    public function handle($items){}

    public function parse($result){
        $headers = explode("\r\n", $result['header']);

        // 获取状态码
        $statusArray = explode(' ', array_shift($headers));

        $responseStatus['agreement'] = array_shift($statusArray);

        $responseStatus['statusCode'] = array_shift($statusArray);

        $responseStatus['statusMessage'] = array_shift($statusArray);

        // 解析html

        foreach($headers as $key => &$headerLine){

            if(!trim($headerLine)){
                unset($headers[$key]);
            };

            $headerLine = explode(': ', $headerLine);

            if(count($headerLine) !== 2){

                unset($headers[$key]);
            }
        }
        $headers = array_column($headers, 1, 0);

        $contentType = $headers['Content-Type'];

        if(strpos($contentType, 'html') !== false){

            $this->resultIsHtml = true;
        }

        $this->responseHeaders = $headers;


        preg_match('/<html(.*)?>([.\S\s]*)<\/html>/',strtolower($result['content']), $htmlDom);

        $htmlDom = '<html>' . $htmlDom[2] . '</html>';

        $dom = new Query($htmlDom);

        $pageTitleResult = $dom->execute('html head title');

        $this->pageTitle = $pageTitleResult[0]->textContent;

        $parseItems = [];

        foreach($this->rules() as $itemProperty => $rule){

            $rulePath = $rule['rule'];

            unset($rule['rule']);

            $parseItems[$itemProperty] = [];

            if(empty($rule)){
                continue;
            }

            $domResults = $dom->execute($rulePath);

            foreach($domResults as $domResult){

                $nowItem = [];

                foreach($rule as $key => $item){


                    $nowItem[$key] = $domResult;

                    $item = explode('.', $item);

                    $value = $nowItem[$key]->$item[0];

                    if(count($item > 1)){

                        if(is_object($value)){

                            $value = $value->getNamedItem($item[1])->value;
                        }
                    }

                    $nowItem[$key] = $value;
                }

                $parseItems[$itemProperty][] = $nowItem;
            }
        }

        return $parseItems;
    }
}