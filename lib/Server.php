<?php

/**
 * Redis Info Server
 *
 * Object for representing a redis server
 *
 * @package    Redis Info
 * @author 	   Osman Üngür <osmanungur@gmail.com>
 * @copyright  2010-2011 Osman Üngür
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version    Version @package_version@
 * @since      Class available since Version 1.0.0
 * @link       http://github.com/osmanungur/redis-info
 */
class Server {

    public $host;
    public $port;
    private $auth;
    public $name;

    public function getHost() {
        return $this->host;
    }

    public function setHost($host) {
        $this->host = $host;
        return $this;
    }

    public function getPort() {
        return $this->port;
    }

    public function setPort($port) {
        $this->port = $port;
        return $this;
    }

    public function getAuth() {
        return $this->auth;
    }

    public function setAuth($auth) {
        $this->auth = $auth;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getHash() {
        return md5($this->getName() . $this->getPort());
    }

}