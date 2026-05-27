<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';

class BaiduPushTest extends TestCase
{
    public function test_class_exists(): void
    {
        $this->assertTrue(class_exists('MaBox_Domestic_Baidu_Push'));
    }

    public function test_run_method_exists(): void
    {
        $this->assertTrue(method_exists('MaBox_Domestic_Baidu_Push', 'run'));
    }

    public function test_active_push_method_exists(): void
    {
        $this->assertTrue(method_exists('MaBox_Domestic_Baidu_Push', 'active_push'));
    }

    public function test_auto_push_js_method_exists(): void
    {
        $this->assertTrue(method_exists('MaBox_Domestic_Baidu_Push', 'auto_push_js'));
    }

    public function test_ajax_batch_push_method_exists(): void
    {
        $this->assertTrue(method_exists('MaBox_Domestic_Baidu_Push', 'ajax_batch_push'));
    }

    public function test_file_has_no_syntax_errors(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $this->assertFileExists($file);

        $output = [];
        $result = 0;
        exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $result);
        $this->assertEquals(0, $result, "PHP syntax error in baidu_push/index.php: " . implode("\n", $output));
    }

    public function test_active_push_is_inside_class(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString('public static function active_push(', $content);
        $this->assertStringContainsString('public static function auto_push_js(', $content);
        $this->assertStringContainsString('public static function ajax_batch_push(', $content);

        $classStart = strpos($content, 'class MaBox_Domestic_Baidu_Push');
        $classEnd = strrpos($content, '}');

        $activePush = strpos($content, 'public static function active_push(');
        $autoPushJs = strpos($content, 'public static function auto_push_js(');
        $ajaxBatch = strpos($content, 'public static function ajax_batch_push(');

        $this->assertGreaterThan($classStart, $activePush, 'active_push should be inside the class body');
        $this->assertLessThan($classEnd, $activePush, 'active_push should be before class closing brace');
        $this->assertGreaterThan($classStart, $autoPushJs, 'auto_push_js should be inside the class body');
        $this->assertLessThan($classEnd, $autoPushJs, 'auto_push_js should be before class closing brace');
        $this->assertGreaterThan($classStart, $ajaxBatch, 'ajax_batch_push should be inside the class body');
        $this->assertLessThan($classEnd, $ajaxBatch, 'ajax_batch_push should be before class closing brace');
    }

    public function test_no_duplicate_closing_brace_after_deprecated(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $content = file_get_contents($file);

        $deprecatedEnd = strpos($content, "self::ajax_batch_push();");
        $this->assertNotFalse($deprecatedEnd, 'ajax_batch_push_deprecated should call ajax_batch_push');

        $afterDeprecated = substr($content, $deprecatedEnd + strlen("self::ajax_batch_push();"), 200);

        $this->assertStringContainsString('public static function active_push(', $afterDeprecated,
            'active_push should follow the deprecated method without extra closing braces');

        $this->assertDoesNotMatchRegularExpression(
            '/}\s*}\s*public static function active_push/',
            $afterDeprecated,
            'There should not be a double closing brace (class prematurely closed) before active_push'
        );
    }

    public function test_run_hooks_active_push(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString("'publish_post'", $content);
        $this->assertStringContainsString("'active_push'", $content);
    }

    public function test_run_hooks_auto_push_js(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString("'wp_footer'", $content);
        $this->assertStringContainsString("'auto_push_js'", $content);
    }

    public function test_push_api_url_format(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString('data.zz.baidu.com/urls', $content);
        $this->assertStringContainsString('urlencode', $content);
    }

    public function test_batch_push_checks_permissions(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString("current_user_can('manage_options')", $content);
    }

    public function test_deprecated_function_exists(): void
    {
        $this->assertTrue(method_exists('MaBox_Domestic_Baidu_Push', 'ajax_batch_push_deprecated'));
    }

    public function test_deprecated_calls_new_method(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString("_deprecated_function", $content);
        $this->assertStringContainsString("self::ajax_batch_push()", $content);
    }

    public function test_rest_batch_push_method_exists(): void
    {
        $this->assertTrue(method_exists('MaBox_Domestic_Baidu_Push', 'rest_batch_push'));
    }

    public function test_rest_route_callback_references_rest_batch_push(): void
    {
        $admin_file = dirname(__FILE__) . '/../../admin/class-magick-mixture-admin.php';
        $content = file_get_contents($admin_file);

        $this->assertStringContainsString("'MaBox_Domestic_Baidu_Push', 'rest_batch_push'", $content);
    }

    public function test_rest_batch_push_returns_wp_error_on_missing_config(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString("WP_Error", $content);
        $this->assertStringContainsString("rest_missing_config", $content);
    }

    public function test_rest_batch_push_supports_urls_and_offset_params(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString("get_param('urls'", $content);
        $this->assertStringContainsString("get_param('offset'", $content);
    }

    public function test_rest_batch_push_uses_rest_ensure_response(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString("rest_ensure_response", $content);
    }

    public function test_rest_batch_push_returns_502_on_baidu_failure(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/baidu_push/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString("rest_baidu_push_failed", $content);
        $this->assertStringContainsString("'status' => 502", $content);
    }
}
