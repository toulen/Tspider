<?php
namespace Tspider;

class TspiderLog{

    const LOG_ON = 1;

    const LOG_OFF = 0;

    // 日志开关
    public $logStatus = self::LOG_ON;

    const LOG_TYPE_DISPLAY_SCREEN = 1;

    const LOG_TYPE_FILE = 2;

    // 日志方式:屏幕显示/文件记录
    public $logType = self::LOG_TYPE_DISPLAY_SCREEN | self::LOG_TYPE_FILE;

    // 日历路径
    public $logFile = './tspider.log';

    static private $obj = null;

    const LOG_ERROR = 32;
    const LOG_SUCCESS = 16;
    const LOG_IN_OUT = 8;
    const LOG_WARNING = 4;
    const LOG_NOTICE = 2;
    const LOG_TEXT = 1;

    public $logLevel = self::LOG_ERROR | self::LOG_SUCCESS | self::LOG_IN_OUT | self::LOG_WARNING | self::LOG_NOTICE | self::LOG_TEXT;

    static public $logLevelArr = [
        self::LOG_ERROR => '[ERROR]',
        self::LOG_SUCCESS => '[SUCCESS]',
        self::LOG_IN_OUT => '[START/END]',
        self::LOG_WARNING => '[WARNING]',
        self::LOG_NOTICE => '[NOTICE]',
        self::LOG_TEXT => ''
    ];

    private function __construct(){}


    // 记录日志
    private function add($message, $logLevel = self::LOG_TEXT){

        if(!($this->logLevel & $logLevel)){
            return false;
        }

        $echoStr = date('[Y-m-d H:i]');

        self::$logLevelArr[$logLevel] && $echoStr .= " " . self::$logLevelArr[$logLevel];

        $echoStr .= " " . $message . "\n";

        // 判断显示方式
        if($this->logType & self::LOG_TYPE_FILE){

            // 日志文件
            try {
                $file = fopen($this->logFile, 'a+');
            }catch(\Exception $e){

                echo "写入日志文件失败!";
                return false;
            }

            fwrite($file, $echoStr);

            fclose($file);
        }

        if($this->logType & self::LOG_TYPE_DISPLAY_SCREEN){

            echo $echoStr;
        }

    }

    public static function log($message, $logLevel = self::LOG_TEXT){
        if(!self::$obj){
            self::$obj = new self();
        }

        self::$obj->add($message, $logLevel);
    }

}