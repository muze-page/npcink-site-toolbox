<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

if (!function_exists('add_action')) {
    function add_action($hook_name, $callback, $priority = 10, $accepted_args = 1)
    {
        $GLOBALS['_test_mabox_actions'][] = array(
            'hook' => $hook_name,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        );
        return true;
    }
}

if (!function_exists('add_filter')) {
    function add_filter($hook_name, $callback, $priority = 10, $accepted_args = 1)
    {
        $GLOBALS['_test_mabox_filters'][] = array(
            'hook' => $hook_name,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        );
        return true;
    }
}

require_once dirname(__DIR__, 2) . '/includes/interface-npcink-toolbox-module.php';
require_once dirname(__DIR__, 2) . '/admin/partials/optimize/medium/ban_auto_size.php';

class MediaAutoSizeFilterTest extends TestCase
{
    protected function setUp(): void
    {
        $GLOBALS['_test_mabox_actions'] = array();
        $GLOBALS['_test_mabox_filters'] = array();
    }

    public function test_intermediate_image_sizes_callback_is_registered_as_a_filter(): void
    {
        Npcink_Toolbox_Medium_Ban_Auto_Size::run();

        $callback = array('Npcink_Toolbox_Medium_Ban_Auto_Size', 'shapeSpace_disable_image_sizes');
        $this->assertHookRegistered(
            $GLOBALS['_test_mabox_filters'],
            'intermediate_image_sizes_advanced',
            $callback
        );
        $this->assertHookNotRegistered(
            $GLOBALS['_test_mabox_actions'],
            'intermediate_image_sizes_advanced',
            $callback
        );
    }

    public function test_filter_removes_only_the_six_core_sizes_and_returns_custom_sizes(): void
    {
        $sizes = array(
            'thumbnail' => array('width' => 150),
            'medium' => array('width' => 300),
            'large' => array('width' => 1024),
            'medium_large' => array('width' => 768),
            '1536x1536' => array('width' => 1536),
            '2048x2048' => array('width' => 2048),
            'theme-hero' => array('width' => 1600, 'crop' => true),
            'plugin-square' => array('width' => 640, 'height' => 640),
        );

        $filtered = Npcink_Toolbox_Medium_Ban_Auto_Size::shapeSpace_disable_image_sizes($sizes);

        $this->assertSame(
            array(
                'theme-hero' => array('width' => 1600, 'crop' => true),
                'plugin-square' => array('width' => 640, 'height' => 640),
            ),
            $filtered
        );
    }

    private function assertHookRegistered($hooks, $hook_name, $callback): void
    {
        foreach ($hooks as $hook) {
            if ($hook['hook'] === $hook_name && $hook['callback'] === $callback) {
                $this->addToAssertionCount(1);
                return;
            }
        }

        $this->fail('Expected hook was not registered: ' . $hook_name);
    }

    private function assertHookNotRegistered($hooks, $hook_name, $callback): void
    {
        foreach ($hooks as $hook) {
            if ($hook['hook'] === $hook_name && $hook['callback'] === $callback) {
                $this->fail('Unexpected hook was registered as an action: ' . $hook_name);
            }
        }

        $this->addToAssertionCount(1);
    }
}
