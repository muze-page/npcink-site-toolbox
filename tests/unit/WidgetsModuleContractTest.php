<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

if (!class_exists('WP_Widget')) {
    class WP_Widget
    {
    }
}

if (!function_exists('add_action')) {
    define('NPCINK_SITE_TOOLBOX_WIDGETS_TEST_ACTION_STUB', true);

    function add_action($hook_name, $callback, $priority = 10, $accepted_args = 1)
    {
        global $_test_widgets_actions;

        $_test_widgets_actions[] = array(
            'hook'          => $hook_name,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args,
        );

        return true;
    }
}

class WidgetsModuleContractTest extends TestCase
{
    private static $pluginDir;

    public static function setUpBeforeClass(): void
    {
        self::$pluginDir = dirname(__DIR__, 2);

        require_once self::$pluginDir . '/includes/interface-npcink-toolbox-module.php';
        require_once self::$pluginDir . '/admin/partials/optimize/widget/index.php';
    }

    protected function setUp(): void
    {
        global $_test_widgets_actions;

        $_test_widgets_actions = array();
    }

    public function test_widgets_module_implements_module_interface(): void
    {
        $this->assertTrue(
            is_subclass_of('Npcink_Toolbox_Widgets', 'Npcink_Toolbox_Module_Interface'),
            'Npcink_Toolbox_Widgets should implement Npcink_Toolbox_Module_Interface'
        );
    }

    public function test_widgets_module_accepts_loader_config_and_registers_existing_hook(): void
    {
        global $_test_widgets_actions;

        Npcink_Toolbox_Widgets::run(array('unused' => true));

        if (!defined('NPCINK_SITE_TOOLBOX_WIDGETS_TEST_ACTION_STUB')) {
            $this->assertNotFalse(
                has_action('widgets_init', array('Npcink_Toolbox_Widgets', 'register_widgets'))
            );
            return;
        }

        $this->assertSame(
            array(
                array(
                    'hook'          => 'widgets_init',
                    'callback'      => array('Npcink_Toolbox_Widgets', 'register_widgets'),
                    'priority'      => 10,
                    'accepted_args' => 1,
                ),
            ),
            $_test_widgets_actions
        );
    }
}
