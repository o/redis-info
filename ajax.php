<?php

require_once 'lib/Autoloader.php';
Autoloader::register();
$json = new JsonResult();
$config = new Config();
foreach ($config->getPool() as $key => $server) {
    try {
        $client = new Client($server);
        $result = $client->getInfo();
        $json->appendResponse($server, $result->getStatistics(), $result->getDatabases());
    } catch (Exception $e) {
        $json->appendError($server, $e->getMessage());
    }
}

echo json_encode($json);