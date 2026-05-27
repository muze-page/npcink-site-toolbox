<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../../admin/partials/domestic/login_security/index.php';

class LoginSecurityTest extends TestCase
{
    public function test_class_exists(): void
    {
        $this->assertTrue(class_exists('MaBox_Domestic_Login_Security'));
    }

    public function test_get_client_ip_method_exists(): void
    {
        $this->assertTrue(method_exists('MaBox_Domestic_Login_Security', 'get_client_ip'));
    }

    public function test_get_client_ip_uses_remote_addr_by_default(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/login_security/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString('REMOTE_ADDR', $content);
    }

    public function test_get_client_ip_does_not_trust_http_client_ip(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/login_security/index.php';
        $content = file_get_contents($file);

        $this->assertStringNotContainsString('HTTP_CLIENT_IP', $content,
            'get_client_ip should NOT trust HTTP_CLIENT_IP header (IP spoofing risk)');
    }

    public function test_get_client_ip_uses_x_forwarded_for_only_with_trusted_proxies(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/login_security/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString('HTTP_X_FORWARDED_FOR', $content);
        $this->assertStringContainsString('trusted_proxies', $content);
    }

    public function test_get_client_ip_validates_ip_format(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/login_security/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString('filter_var', $content);
        $this->assertStringContainsString('FILTER_VALIDATE_IP', $content);
    }

    public function test_get_client_ip_returns_default_on_failure(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/login_security/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString("'0.0.0.0'", $content);
    }

    public function test_run_method_exists(): void
    {
        $this->assertTrue(method_exists('MaBox_Domestic_Login_Security', 'run'));
    }

    public function test_has_fail_limit_feature(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/login_security/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString('fail_limit_enabled', $content);
        $this->assertStringContainsString('fail_limit_count', $content);
    }

    public function test_has_ip_lock_feature(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/login_security/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString('ip_lock_enabled', $content);
        $this->assertStringContainsString('ip_lock_count', $content);
    }

    public function test_has_custom_login_feature(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/login_security/index.php';
        $content = file_get_contents($file);

        $this->assertStringContainsString('custom_login_enabled', $content);
        $this->assertStringContainsString('custom_login_slug', $content);
    }

    public function test_file_has_no_syntax_errors(): void
    {
        $file = dirname(__FILE__) . '/../../admin/partials/domestic/login_security/index.php';
        $this->assertFileExists($file);

        $output = [];
        $result = 0;
        exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $result);
        $this->assertEquals(0, $result, "PHP syntax error: " . implode("\n", $output));
    }
}
