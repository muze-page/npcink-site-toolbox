<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    magick_mixtrue
 * @subpackage magick_mixtrue/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    magick_mixtrue
 * @subpackage magick_mixtrue/public
 * @author     Your Name <email@example.com>
 */
class Magick_Mixtrue_Public
{

    /**
     * The ID of this plugin.
     *
     */
    private $magick_mixtrue;

    /**
     * The version of this plugin.
     *
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     */
    public function __construct($magick_mixtrue, $version)
    {

        $this->magick_mixtrue = $magick_mixtrue;
        $this->version = $version;
        $this->load();
        $this->run();

    }
    public function load()
    {
        //加载表情包
        require_once plugin_dir_path(__FILE__) . 'partials/class-mm-display.php';
        //自定义的一为登录页
        require_once plugin_dir_path(__FILE__) . 'partials/class-mm-login.php';
    }
    public function run()
    {
        //加载前台表情
        Magick_Mixtrue_Display::run();

        //加载登录页
        Magick_Mixtrue_Login::run();
    }





}
