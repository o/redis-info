<?php

/**
 * Redis Info Autoloader
 *
 * This class used for registering and autoloading libraries
 *
 * @package    Redis Info
 * @author 	   Osman Üngür <osmanungur@gmail.com>
 * @copyright  2010-2011 Osman Üngür
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version    Version @package_version@
 * @since      Class available since Version 1.0.0
 * @link       http://github.com/osmanungur/redis-info
 */
class Autoloader {

    protected static $isRegistered;
    protected static $callback = array('Autoloader', 'load');
    protected static $path;

    public static function register() {
        if (self::isRegistered()) {
            return false;
        }

        self::$isRegistered = spl_autoload_register(self::$callback);

        return self::$isRegistered;
    }

    public static function isRegistered() {
        return self::$isRegistered;
    }

    public static function load($class) {
        $file_path = self::getPath() . DIRECTORY_SEPARATOR . $class . '.php';

        if (!file_exists($file_path)) {
            return false;
        }

        require_once $file_path;
    }

    public static function getPath() {
        if (!self::$path) {
            self::$path = realpath(dirname(__FILE__));
        }

        return self::$path;
    }

}