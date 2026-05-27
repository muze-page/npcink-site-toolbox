<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../../includes/class-mabox-diagnostics.php';

class DiagnosticsCdnTest extends TestCase
{
    private function mockWordPressFunctions(array $options = array()): void
    {
        if (!function_exists('get_bloginfo')) {
            function get_bloginfo($show = '') { return '6.4'; }
        }
        if (!function_exists('wp_remote_get')) {
            function wp_remote_get($url, $args = array()) {
                return array('response' => array('code' => 200));
            }
        }
        if (!function_exists('wp_remote_retrieve_response_code')) {
            function wp_remote_retrieve_response_code($response) {
                return is_array($response) && isset($response['response']['code']) ? $response['response']['code'] : 200;
            }
        }
        if (!function_exists('is_wp_error')) {
            function is_wp_error($thing) { return false; }
        }
        if (!function_exists('wp_using_ext_object_cache')) {
            function wp_using_ext_object_cache() { return false; }
        }
        if (!function_exists('home_url')) {
            function home_url($path = '') { return 'https://example.com' . $path; }
        }
        if (!function_exists('get_rest_url')) {
            function get_rest_url($blog_id = null, $path = '/', $scheme = 'rest') {
                return 'https://example.com/wp-json/';
            }
        }
        if (!function_exists('__')) {
            function __($text, $domain = 'default') { return $text; }
        }

        $GLOBALS['_test_option_store'] = array_merge(array(
            MAGICK_TOOLBOX_ACTIVE_MODULES => array(),
        ), $options);
    }

    public function test_calculate_score_with_no_cdn_replacements(): void
    {
        $method = new ReflectionMethod('MaBox_Diagnostics', 'calculate_score');

        $config = array(
            'optimize' => array('site' => array()),
        );

        $env = array(
            'php_version'        => '8.1',
            'wp_version'         => '6.4',
            'permalink'          => '/%postname%/',
            'object_cache'       => true,
            'rest_api_available' => true,
        );

        $score = $method->invoke(null, $config, $env);
        $this->assertEquals(57, $score, 'base 60 - 3(no CDN replacements)');
    }

    public function test_calculate_score_with_all_cdn_replacements(): void
    {
        $method = new ReflectionMethod('MaBox_Diagnostics', 'calculate_score');

        $config = array(
            'optimize' => array(
                'site' => array(
                    'cdn_gravatar' => true,
                    'cdn_google_fonts' => true,
                    'cdn_google_ajax' => true,
                ),
            ),
        );

        $env = array(
            'php_version'        => '8.1',
            'wp_version'         => '6.4',
            'permalink'          => '/%postname%/',
            'object_cache'       => true,
            'rest_api_available' => true,
        );

        $score = $method->invoke(null, $config, $env);
        $this->assertEquals(65, $score, 'base 60 + 5(all CDN replaced)');
    }

    public function test_calculate_score_with_partial_cdn_replacements(): void
    {
        $method = new ReflectionMethod('MaBox_Diagnostics', 'calculate_score');

        $config = array(
            'optimize' => array(
                'site' => array(
                    'cdn_gravatar' => true,
                    'cdn_google_fonts' => true,
                ),
            ),
        );

        $env = array(
            'php_version'        => '8.1',
            'wp_version'         => '6.4',
            'permalink'          => '/%postname%/',
            'object_cache'       => true,
            'rest_api_available' => true,
        );

        $score = $method->invoke(null, $config, $env);
        $this->assertEquals(60, $score, '2/3 replaced: no bonus, no penalty');
    }

    public function test_diagnostic_items_include_domestic_environment(): void
    {
        $this->mockWordPressFunctions();

        $method = new ReflectionMethod('MaBox_Diagnostics', 'get_diagnostic_items');

        $config = array();
        $env = array('php_version' => '8.1', 'wp_version' => '6.4', 'permalink' => '/%postname%/', 'object_cache' => true, 'rest_api_available' => true);
        $active_modules = array();
        $tiers = array();

        $items = $method->invoke(null, $config, $env, $active_modules, $tiers);

        $ids = array_column($items, 'id');
        $this->assertContains('domestic_environment', $ids,
            'domestic_environment diagnostic item should be present');
    }

    public function test_domestic_environment_item_status_warning_when_no_cdn(): void
    {
        $this->mockWordPressFunctions();

        $method = new ReflectionMethod('MaBox_Diagnostics', 'get_diagnostic_items');

        $config = array('optimize' => array('site' => array()));
        $env = array('php_version' => '8.1', 'wp_version' => '6.4', 'permalink' => '/%postname%/', 'object_cache' => true, 'rest_api_available' => true);

        $items = $method->invoke(null, $config, $env, array(), array());

        $envItem = null;
        foreach ($items as $item) {
            if ($item['id'] === 'domestic_environment') {
                $envItem = $item;
                break;
            }
        }

        $this->assertNotNull($envItem);
        $this->assertEquals('warning', $envItem['status']);
        $this->assertStringContainsString('未开启', $envItem['message']);
    }

    public function test_domestic_environment_item_status_good_when_all_cdn(): void
    {
        $this->mockWordPressFunctions();

        $method = new ReflectionMethod('MaBox_Diagnostics', 'get_diagnostic_items');

        $config = array(
            'optimize' => array(
                'site' => array(
                    'cdn_gravatar' => true,
                    'cdn_google_fonts' => true,
                    'cdn_google_ajax' => true,
                ),
            ),
        );
        $env = array('php_version' => '8.1', 'wp_version' => '6.4', 'permalink' => '/%postname%/', 'object_cache' => true, 'rest_api_available' => true);

        $items = $method->invoke(null, $config, $env, array(), array());

        $envItem = null;
        foreach ($items as $item) {
            if ($item['id'] === 'domestic_environment') {
                $envItem = $item;
                break;
            }
        }

        $this->assertNotNull($envItem);
        $this->assertEquals('good', $envItem['status']);
        $this->assertStringContainsString('全部开启', $envItem['message']);
    }
}