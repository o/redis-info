<?php

/**
 * Redis Info JsonResult
 *
 * Used for creating JavaScript Object Notation formatted output for browsers
 *
 * @package    Redis Info
 * @author 	   Osman Üngür <osmanungur@gmail.com>
 * @copyright  2010-2011 Osman Üngür
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version    Version @package_version@
 * @since      Class available since Version 1.0.0
 * @link       http://github.com/osmanungur/redis-info
 */
class JsonResult extends ArrayObject {
    const RESULT_SUCCESS = 'success';
    const RESULT_ERROR = 'error';

    const KEY_RESULT = 'result';
    const KEY_SERVER = 'server';
    const KEY_MESSAGE = 'message';
    const KEY_STATISTICS = 'statistics';
    const KEY_DATABASES = 'databases';

    public function appendResponse(Server $server, $statistics, $databases) {
        $this->append( new ArrayObject(array(self::KEY_RESULT => self::RESULT_SUCCESS, self::KEY_SERVER => $server, self::KEY_STATISTICS => $statistics, self::KEY_DATABASES => $databases)) );
    }

    public function appendError(Server $server, $errstr) {
        $this->append( new ArrayObject(array(self::KEY_RESULT => self::RESULT_ERROR, self::KEY_SERVER => $server, self::KEY_MESSAGE => $errstr)) );
    }

}