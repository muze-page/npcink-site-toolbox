<?php
defined('ABSPATH') || exit;

if (!class_exists('Npcink_Toolbox_Baidu_Tonji')) {
    class Npcink_Toolbox_Baidu_Tonji implements Npcink_Toolbox_Module_Interface
    {
        private static $option;

        public static function run($config = array())
        {
            self::$option = isset($config['baidu_tonji']) && is_string($config['baidu_tonji'])
                ? $config['baidu_tonji']
                : '';
            add_action('wp_footer', array(__CLASS__, 'render'), 999);
        }

        public static function render()
        {
            if (!empty(self::$option)) {
                echo '<script>var _hmt=_hmt||[];(function(){var hm=document.createElement("script");hm.src="https://hm.baidu.com/hm.js?' . esc_js(self::$option) . '";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(hm,s)})()</script>' . "\n";
            }
        }
    }
}
