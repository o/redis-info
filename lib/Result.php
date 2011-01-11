<?php

/**
 * Redis Info Result
 *
 * Parser for redis info command output  
 *
 * @package    Redis Info
 * @author 	   Osman Üngür <osmanungur@gmail.com>
 * @copyright  2010-2011 Osman Üngür
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version    Version @package_version@
 * @since      Class available since Version 1.0.0
 * @link       http://github.com/osmanungur/redis-info
 */
class Result {
    const DB_KEY = 'db';
    const DB_PATTERN = '/keys=(?<totalKey>\d+),expires=(?<willExpire>\d+)/';
    private $response;
    private $statistics;
    private $databases;
    private $knownInfoPatterns = array(
        'redis_version',
        'redis_git_sha1',
        'redis_git_dirty',
        'arch_bits',
        'multiplexing_api',
        'process_id',
        'uptime_in_seconds',
        'uptime_in_days',
        'connected_clients',
        'connected_slaves',
        'blocked_clients',
        'used_memory',
        'used_memory_human',
        'changes_since_last_save',
        'bgsave_in_progress',
        'last_save_time',
        'bgrewriteaof_in_progress',
        'total_connections_received',
        'total_commands_processed',
        'expired_keys',
        'hash_max_zipmap_entries',
        'hash_max_zipmap_value',
        'pubsub_channels',
        'pubsub_patterns',
        'vm_enabled',
        'role');

    public function __construct($response) {
        $this->setResponse($response);
        $this->setStatistics();
        $this->setDatabases();
    }

    public function getResponse() {
        return $this->response;
    }

    public function setResponse($response) {
        $lines = new ArrayObject(explode(Client::EOL, $response));
        $this->response = $lines;
    }

    public function getStatistics() {
        return $this->statistics;
    }

    public function setStatistics() {
        $statistics = new ArrayObject();
        foreach ($this->getResponse() as $value) {
            $line = new ArrayObject(explode(':', $value, 2));
            if (in_array($line->offsetGet(0), $this->getKnownInfoPatterns())) {
                $statistics->offsetSet($line->offsetGet(0), $line->offsetGet(1));
            }
        }
        $this->statistics = $statistics;
    }

    public function getDatabases() {
        return $this->databases;
    }

    public function setDatabases() {
        $databases = new ArrayObject();
        foreach ($this->getResponse() as $value) {
            $line = new ArrayObject(explode(':', $value, 2));
            if (strpos($line->offsetGet(0), self::DB_KEY) !== FALSE) {
                preg_match(self::DB_PATTERN, $line->offsetGet(1), $matches);
                $databases->offsetSet($line->offsetGet(0), new ArrayObject($matches));
            }
        }
        $this->databases = $databases;
    }

    public function getKnownInfoPatterns() {
        return $this->knownInfoPatterns;
    }

}