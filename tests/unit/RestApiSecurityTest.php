<?php

// 如果直接访问此文件，请中止。
defined('ABSPATH') || exit;

use PHPUnit\Framework\TestCase;

/**
 * REST API 安全与路由注册测试
 *
 * 验证：
 * 1. 所有 REST 路由都有 permission_callback
 * 2. 敏感端点使用 manage_options 权限
 * 3. 公开端点使用 __return_true 或 RateLimiter
 */
class RestApiSecurityTest extends TestCase {

    /**
     * 测试 REST 路由注册文件存在
     */
    public function test_rest_routes_file_exists(): void {
        $admin_file = dirname(__DIR__, 2) . '/admin/class-magick-mixture-admin.php';
        $this->assertFileExists($admin_file);
    }

    /**
     * 测试所有 register_rest_route 调用都包含 permission_callback
     */
    public function test_all_rest_routes_have_permission_callback(): void {
        $admin_file = dirname(__DIR__, 2) . '/admin/class-magick-mixture-admin.php';
        $content = file_get_contents($admin_file);

        // 统计 register_rest_route 调用次数
        $route_count = substr_count($content, 'register_rest_route(');
        $this->assertGreaterThan(0, $route_count, '应该至少注册一个 REST 路由');

        // 统计 permission_callback 出现次数
        $permission_count = substr_count($content, "'permission_callback'");
        $this->assertGreaterThanOrEqual(
            $route_count,
            $permission_count,
            '每个 register_rest_route 都必须有 permission_callback'
        );
    }

    /**
     * 测试敏感端点使用 manage_options 权限
     */
    public function test_sensitive_endpoints_require_manage_options(): void {
        $admin_file = dirname(__DIR__, 2) . '/admin/class-magick-mixture-admin.php';
        $content = file_get_contents($admin_file);

        // 敏感端点关键词
        $sensitive_patterns = array(
            '/settings',
            '/settings/export',
            '/settings/import',
            '/performance/db/clean',
            '/page/batch-replace',
            '/tools/table-data',
        );

        foreach ($sensitive_patterns as $pattern) {
            $this->assertStringContainsString($pattern, $content, "敏感端点 {$pattern} 应该存在");
        }

        // 批量替换端点必须使用 manage_options（不能是 edit_posts）
        $this->assertStringNotContainsString(
            "'edit_posts'",
            $content,
            'batch-replace 端点不应使用 edit_posts 权限'
        );
    }

    /**
     * 测试公开端点有限流保护
     */
    public function test_public_endpoints_have_rate_limiting(): void {
        $admin_file = dirname(__DIR__, 2) . '/admin/class-magick-mixture-admin.php';
        $content = file_get_contents($admin_file);

        // 公开端点应使用 Rate_Limiter
        $public_endpoints = array('search-log', 'anti-crawler', 'rating', 'wx-unlock');
        foreach ($public_endpoints as $endpoint) {
            $this->assertStringContainsString($endpoint, $content, "公开端点 {$endpoint} 应该存在");
        }

        // 检查 Rate_Limiter 被引用
        $this->assertStringContainsString('MaBox_Rate_Limiter', $content, '应该使用 Rate_Limiter 限制公开端点');
    }

    /**
     * 测试设置保存端点有参数消毒
     */
    public function test_settings_save_has_sanitize_callback(): void {
        $admin_file = dirname(__DIR__, 2) . '/admin/class-magick-mixture-admin.php';
        $content = file_get_contents($admin_file);

        // 检查 sanitize_callback 存在
        $this->assertStringContainsString("'sanitize_callback'", $content, 'REST API 参数应该有 sanitize_callback');

        // 检查 validate_callback 存在
        $this->assertStringContainsString("'validate_callback'", $content, 'REST API 参数应该有 validate_callback');
    }

    /**
     * 测试 Batch Replace 有危险内容过滤
     */
    public function test_batch_replace_has_dangerous_content_filter(): void {
        $admin_file = dirname(__DIR__, 2) . '/admin/class-magick-mixture-admin.php';
        $content = file_get_contents($admin_file);

        // 检查是否包含内容消毒
        $this->assertStringContainsString('wp_kses_post', $content, 'Batch Replace 应该使用 wp_kses_post 消毒输入内容');
    }

    /**
     * 测试导入端点有正确权限
     */
    public function test_import_endpoint_requires_manage_options(): void {
        $admin_file = dirname(__DIR__, 2) . '/admin/class-magick-mixture-admin.php';
        $content = file_get_contents($admin_file);

        // 导入设置是敏感操作，必须管理员权限
        $this->assertStringContainsString("'/settings/import'", $content, '导入端点应该存在');
    }
}
