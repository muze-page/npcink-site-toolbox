<?php

/**
 * 功能：复制按钮
 * 来源：
 */
if (!class_exists('MaBox_ShortCode_Single_Copy')) {
    class MaBox_ShortCode_Single_Copy
    {
        public static function run()
        {


            //添加短代码
            add_shortcode('mabox_copy_btn', array(__CLASS__, 'caption_shortcode'));

            // 判断当前页面是否有 mabox_copy_btn 短代码，如果有则加载 加载前端资源
            add_action('wp_enqueue_scripts', function () {
                global $post;
                if (has_shortcode($post->post_content, 'mabox_copy_btn')) {
                    self::load_js();
                }
            });
        }
        //解析短代码
        public static function caption_shortcode($atts, $content = null)
        {
            $a = shortcode_atts(array(
                'copy' => '需复制的内容',
                'alert' => '复制成功提示',
                'link' => '跳转链接',
                // ...etc
            ), $atts);
            $copy = esc_attr($a['copy']);
            $alert = esc_attr($a['alert']);
            $link = esc_url($a['link']);
            //递归解析短代码
            // 生成按钮的 HTML 代码，使用 htmlspecialchars 进行安全输出
            $button_html = '<button onClick="copys(&quot;' . htmlspecialchars($copy) . '&quot;, &quot;' . htmlspecialchars($alert) . '&quot;, &quot;' . htmlspecialchars($link) . '&quot;)">'
                . $content . '</button>';

            return $button_html;
        }

        //加载JS
        public static function load_js()
        {
            //判断下，是否在前端页中
            if (is_admin()) {
                return;
            }

            //准备css
            $build_css =  plugin_dir_url(__DIR__) . 'single_copy/copy.css';
            wp_enqueue_style(
                MAGICK_MIXTURE_NAME . '_public_single_copy_css',
                $build_css,
                array(),
                MAGICK_MIXTURE_VERSION,
                false
            );
            //准备js 
            $build_js =  plugin_dir_url(__DIR__) . 'single_copy/copy.js';
            wp_enqueue_script(
                MAGICK_MIXTURE_NAME . '_public_single_copy_js',
                $build_js,
                array(),
                MAGICK_MIXTURE_VERSION,
                true
            );
        }
    }
}
