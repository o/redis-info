Redis Info
==========

Redis Info is an easy to configure, web-based realtime monitoring tool for Redis servers written in PHP. It provides nice front-end for access to statistics and server status.

Installing
=============
Download and place all files to your webserver. It needs at least PHP 5.2 with JSON extension installed.

Configuration
=============

You can add / edit your Redis servers using config.ini file. An example configuration file looks like this :

<pre>
[Node 76]
host = http://node-76.redis-server/
port = 6379
auth = password

[Master]
host = http://redis-server-master/
port = 6501
auth = foobared
</pre>