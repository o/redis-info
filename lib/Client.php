<?php

/**
 * Redis Info Client
 *
 * Class used for connecting and fetching information from redis servers
 *
 * @package    Redis Info
 * @author 	   Osman Üngür <osmanungur@gmail.com>
 * @copyright  2010-2011 Osman Üngür
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version    Version @package_version@
 * @since      Class available since Version 1.0.0
 * @link       http://github.com/osmanungur/redis-info
 */
class Client {

    private $socket;
    private $server;

    const COMMAND_INFO = 'INFO';
    const COMMAND_AUTH = 'AUTH';
    const EOL = "\r\n";
    const TIMEOUT = 5;
    const TCP_PREFIX = 'tcp://';
    const SEPERATOR = ':';
    const REPLY_STATUS_BULK = '$';
    const REPLY_STATUS_SUCCESS = '+';
    const REPLY_STATUS_ERROR = '-';
    const REPLY_OK = 'OK';

    public function __construct($server) {
        $this->setServer($server);
        $this->connect();
        if ($this->getServer()->getAuth()) $this->auth();
    }

    public function connect() {
        $connection = @stream_socket_client(self::TCP_PREFIX . $this->getServer()->getHost() . self::SEPERATOR . $this->getServer()->getPort(), $errno, $errstr, self::TIMEOUT);
        if ($connection) {
            $this->setSocket($connection);
            return true;
        } else {
            throw new Exception(sprintf('Cant connect to Redis server %s:%s Error : %s, %s', $this->getServer()->getHost(), $this->getServer()->getPort(), $errno, $errstr), 1);
        }
    }

    public function write($command) {
        $write = fwrite($this->getSocket(), $command . self::EOL);
        if ($write) {
            return true;
        } else {
            throw new Exception('No bytes have been written', 1);
        }
    }

    public function auth() {
        $this->write(self::COMMAND_AUTH . ' ' . $this->getServer()->getAuth());
        $reply = $this->readLine();
        $kind = substr($reply, 0, 1);
        $data = substr($reply, 1);
        if ($kind == self::REPLY_STATUS_SUCCESS && $data == self::REPLY_OK) {
            return true;
        } else {
            throw new Exception(sprintf('Authorization failed to Redis server : %s', $this->getServer()->getName()), 1);
        }
    }

    public function readLine() {
        $line = trim(fgets($this->getSocket()));
        if ($line) {
            return $line;
        } else {
            throw new Exception('Cant read line from socket.', 1);
        }
    }

    public function readBulkReply($length) {
        $response = trim(fread($this->getSocket(), $length));
        if ($response) {
            return $response;
        } else {
            throw new Exception('Cant read reply from socket.', 1);
        }
    }

    public function getInfo() {
        $this->write(self::COMMAND_INFO);
        $reply = $this->readLine();
        $kind = substr($reply, 0, 1);
        $data = substr($reply, 1);
        if ($kind == self::REPLY_STATUS_BULK) {
            return new Result($this->readBulkReply($data));
        } else {
            throw new Exception(sprintf("Redis server returns an unexpected response : %s", substr($data, 4)), 1);
        }
    }

    public function getStreamInfo() {
        return new ArrayObject(stream_get_meta_data($this->getSocket()));
    }

    public function getSocket() {
        return $this->socket;
    }

    public function setSocket($socket) {
        $this->socket = $socket;
    }

    public function getServer() {
        return $this->server;
    }

    public function setServer(Server $server) {
        if ($server instanceof Server) {
            $this->server = $server;
        } else {
            throw new Exception("Error Processing Request", 1);
        }
    }

}