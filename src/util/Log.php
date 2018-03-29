<?php
namespace Dingtalk\util;
class Log{
    public function __construct()
    {
    }

    public static function i($msg)
    {
        self::write('I', $msg);
    }

    public static function e($msg)
    {
        self::write('E', $msg);
    }

    private static function write($level, $msg)
    {
//        $filename = Config::get('DIR_ROOT') . "corp.log";
        $filename = dirname(__DIR__) . "/corp.log";
        $logFile = fopen($filename, "aw");
        fwrite($logFile, $level . "/" . date(" Y-m-d h:i:s") . "  " . $msg . "\n");
        fclose($logFile);
    }
}