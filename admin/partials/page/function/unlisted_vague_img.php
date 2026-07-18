<?php

defined('ABSPATH') || exit;

if (!class_exists('Npcink_Toolbox_Unlisted_Vague_Img')) {
    class Npcink_Toolbox_Unlisted_Vague_Img implements Npcink_Toolbox_Module_Interface
    {
        public static function run($config = array())
        {
            add_action('wp_head', array(__CLASS__, 'render'), 999);
        }

        public static function render()
        {
            if (!Npcink_Toolbox_Helpers::is_logged_in()) {
                echo '<style>.entry-content img{-webkit-filter:blur(10px)!important;-moz-filter:blur(10px)!important;-ms-filter:blur(10px)!important;filter:blur(6px)!important}.entry-content img:before{content:"登录可见"}</style>' . "\n";
            }
        }
    }
}
