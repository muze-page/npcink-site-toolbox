<?php
defined('ABSPATH') || exit;

require_once dirname(__DIR__, 3) . '/includes/interface-npcink-toolbox-module.php';

if (!class_exists('Npcink_Toolbox_Loader_Contract_Test_Module')) {
    class Npcink_Toolbox_Loader_Contract_Test_Module implements Npcink_Toolbox_Module_Interface {
        public static $argument_count = null;
        public static $received_config = null;

        public static function run($config = array()) {
            self::$argument_count = func_num_args();
            self::$received_config = $config;
        }
    }
}

if (!class_exists('Npcink_Toolbox_Loader_Non_Interface_Test_Module')) {
    class Npcink_Toolbox_Loader_Non_Interface_Test_Module {
        public static $did_run = false;

        public static function run($config = array()) {
            self::$did_run = true;
        }
    }
}
