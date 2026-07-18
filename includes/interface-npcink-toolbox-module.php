<?php
// 如果直接访问此文件，请中止。
defined('ABSPATH') || exit;
/**
 * 功能模块接口契约
 *
 * 所有功能模块必须实现此接口。
 * 模块通过静态方法 run() 初始化，可选接收配置参数。
 *
 * @since 2.3.1
 */
if (!interface_exists('Npcink_Toolbox_Module_Interface')) {
    interface Npcink_Toolbox_Module_Interface {
        /**
         * 初始化模块
         *
         * @param array $config 模块配置（可选）
         * @return void
         */
        public static function run($config = array());
    }
}
