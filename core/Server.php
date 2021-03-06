<?php
/**
 * Created by PhpStorm.
 * User: XiaoLin
 * Date: 2018-06-29
 * Time: 1:46 PM
 */

require_once __DIR__ . '/Event.php';
require_once __DIR__ . '/Storage.php';
require_once __DIR__ . '/Method.php';

class Server
{
    public function start()
    {
        $int = 0;

        /**
         * 创建必要的MySQL表
         */
        $db = new \Buki\Pdox(CONFIG['database']);
        $db->query("CREATE TABLE if not exists `image_file_id` (`id` int(11) PRIMARY KEY AUTO_INCREMENT, `qq_img_id` text, `qq_img_url` text, `tg_file_id` text, `time` int(11) DEFAULT NULL);");
        $db->query("CREATE TABLE if not exists `user_info` (`id` int(11) PRIMARY KEY AUTO_INCREMENT,`user_id` bigint(20) NOT NULL,`qq_group_id` bigint(20) NOT NULL,`card` text,`flush_time` int(11) NOT NULL);");
        unset($db);

        define('MASTER_ID',json_decode(file_get_contents(CONFIG['coolq']['http_url'] . '/get_login_info'),true)['data']['user_id']);

        /**
         * 新建WS服务器
         */
        $server = new swoole_websocket_server(CONFIG['websocket']['host'], CONFIG['websocket']['port']);

        /**
         * 与客户端握手时通知
         */
        $server->on('open', function (swoole_websocket_server $server, $request) {
            echo "与客户端 {$request->fd} 号成功握手\n";
            Method::log(1,"与客户端 {$request->fd} 号成功握手");
        });

        /**
         * [Main]客户端发送消息时
         */
        $server->on('Message', function (swoole_websocket_server $server, $frame) use($int) {
            global $int;
            if (($int = $int + 1) >= CONFIG['program']['restart_count']) exit("\n\n计数达到 " . $int . " , 结束进程\n\n");
            echo "-----------[{$int}]----------\n";
            $data = json_decode($frame->data,true);

            /**
             * Log
             */
            Method::log(0,'CoolQ Receive Raw Data: ' . $frame->data);

            /**
             * 发往 /core/Event.php handler 分析消息类型
             */
            Event::handler($data);
            echo "\n";
        });

        /**
         * 与客户端断开时通知
         */
        $server->on('close', function ($server, $fd) {
            echo "与客户端 {$fd} 号失去连接\n";
            Method::log(3,"与客户端 {$fd} 号失去连接");
        });

        /**
         * 启动WS服务器
         */
        $server->start();
    }
}