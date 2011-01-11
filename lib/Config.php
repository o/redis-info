<?php

/**
 * Config
 *
 * Parses config file and creates iteratable server pool
 *
 * @package    Redis Info
 * @author 	   Osman Üngür <osmanungur@gmail.com>
 * @copyright  2010-2011 Osman Üngür
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version    Version @package_version@
 * @since      Class available since Version 1.0.0
 * @link       http://github.com/osmanungur/redis-info
 */
class Config {
    const SERVER_PROPERTY_HOST = 'host';
    const SERVER_PROPERTY_PORT = 'port';
    const SERVER_PROPERTY_AUTH = 'auth';

    const CONFIG_FILE_PATH = './config.ini';
    const PROCESS_SECTIONS = true;

    private $configuration;
    private $pool;

    public function __construct() {
        $this->setConfiguration();
        $this->setPool();
    }

    public function getConfiguration() {
        return $this->configuration;
    }

    public function setConfiguration() {
        $this->configuration = parse_ini_file(self::CONFIG_FILE_PATH, self::PROCESS_SECTIONS);
    }

    public function getPool() {
        return $this->pool;
    }

    public function setPool() {
        $pool = new Pool();
        foreach ($this->getConfiguration() as $key => $value) {
            $array = new ArrayObject($value);
            $server = new Server();
            $server->setHost($array->offsetGet(self::SERVER_PROPERTY_HOST))
                    ->setPort($array->offsetGet(self::SERVER_PROPERTY_PORT))
                    ->setName($key);
            if ($array->offsetExists(self::SERVER_PROPERTY_AUTH)) {
                $server->setAuth($array->offsetGet(self::SERVER_PROPERTY_AUTH));
            }
            $pool->attach($server);
        }
        $this->pool = $pool;
    }

}