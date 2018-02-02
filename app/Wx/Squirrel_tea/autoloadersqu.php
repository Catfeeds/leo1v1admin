<?php
namespace Squirrel_tea;
/**
 *
 * 自动载入函数
 * Created by Lane.
 * User: lane
 * Date: 14-10-15
 * Time: 下午6:13
 * E-mail: lixuan868686@163.com
 * WebSite: http://www.lanecn.com
 */
class Autoloadersqu{
    const NAMESPACE_PREFIX_SST = 'Squirrel_tea\\';
    /**
     * 向PHP注册在自动载入函数
     */
    public static function register(){
        spl_autoload_register(array(new self, 'autoload'));
    }

    /**
     * 根据类名载入所在文件
     */
    public static function autoload($className){
        $namespacePrefixStrlen = strlen(self::NAMESPACE_PREFIX_SST);
        if(strncmp(self::NAMESPACE_PREFIX_SST, $className, $namespacePrefixStrlen) === 0){
            $className = strtolower($className);
            $filePath = str_replace('\\', DIRECTORY_SEPARATOR, substr($className, $namespacePrefixStrlen));
            $filePath = realpath(__DIR__ . (empty($filePath) ? '' : DIRECTORY_SEPARATOR) . $filePath . '.php');
            if(file_exists($filePath)){
                require_once $filePath;
            }else{
                echo $filePath;
            }
        }
    }
}