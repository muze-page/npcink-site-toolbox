<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * MaBox_Download_SQL_Table 数据库导出安全测试
 *
 * 测试敏感字段脱敏、表名白名单等安全逻辑
 */
class MaBox_Database_Export_Security_Test extends TestCase {

    /**
     * 测试 Download SQL Table 类存在
     */
    public function test_class_exists(): void {
        $this->assertTrue(class_exists('MaBox_Download_SQL_Table'));
    }

    /**
     * 测试敏感字段列表已定义
     */
    public function test_sensitive_fields_defined(): void {
        $reflection = new ReflectionClass('MaBox_Download_SQL_Table');
        $property = $reflection->getProperty('sensitive_fields');
        $property->setAccessible(true);
        $fields = $property->getValue();

        $this->assertIsArray($fields);
        $this->assertContains('user_pass', $fields);
        $this->assertContains('user_email', $fields);
    }

    /**
     * 测试允许的表名前缀已定义
     */
    public function test_allowed_prefixes_defined(): void {
        $reflection = new ReflectionClass('MaBox_Download_SQL_Table');
        $property = $reflection->getProperty('allowed_prefixes');
        $property->setAccessible(true);
        $prefixes = $property->getValue();

        $this->assertIsArray($prefixes);
        $this->assertContains('mabox_', $prefixes);
        $this->assertContains('wp_mabox_', $prefixes);
    }

    /**
     * 测试 is_field_sensitive 方法存在
     */
    public function test_is_field_sensitive_exists(): void {
        $this->assertTrue(method_exists('MaBox_Download_SQL_Table', 'is_field_sensitive'));
    }

    /**
     * 测试 mask_value 方法存在
     */
    public function test_mask_value_exists(): void {
        $this->assertTrue(method_exists('MaBox_Download_SQL_Table', 'mask_value'));
    }

    /**
     * 测试 is_table_allowed 方法存在
     */
    public function test_is_table_allowed_exists(): void {
        $this->assertTrue(method_exists('MaBox_Download_SQL_Table', 'is_table_allowed'));
    }

    /**
     * 测试 is_field_sensitive 检测密码字段
     */
    public function test_detects_password_fields(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'is_field_sensitive');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke(null, 'user_pass'));
        $this->assertTrue($method->invoke(null, 'password'));
        $this->assertTrue($method->invoke(null, 'user_password'));
    }

    /**
     * 测试 is_field_sensitive 检测密钥字段
     */
    public function test_detects_secret_fields(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'is_field_sensitive');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke(null, 'api_secret'));
        $this->assertTrue($method->invoke(null, 'secret_key'));
        $this->assertTrue($method->invoke(null, 'access_token'));
    }

    /**
     * 测试 is_field_sensitive 检测 API key 字段
     */
    public function test_detects_api_key_fields(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'is_field_sensitive');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke(null, 'api_key'));
        $this->assertTrue($method->invoke(null, 'api_secret'));
        $this->assertTrue($method->invoke(null, 'refresh_token'));
    }

    /**
     * 测试 is_field_sensitive 对普通字段返回 false
     */
    public function test_non_sensitive_fields_return_false(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'is_field_sensitive');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke(null, 'post_title'));
        $this->assertFalse($method->invoke(null, 'post_content'));
        $this->assertFalse($method->invoke(null, 'ID'));
        $this->assertFalse($method->invoke(null, 'post_date'));
    }

    /**
     * 测试 mask_value 对密码返回掩码
     */
    public function test_masks_password_values(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'mask_value');
        $method->setAccessible(true);

        $this->assertEquals('***masked***', $method->invoke(null, 'user_pass', 'secret123'));
        $this->assertEquals('***masked***', $method->invoke(null, 'password', 'mypassword'));
    }

    /**
     * 测试 mask_value 对邮箱进行脱敏
     */
    public function test_masks_email_values(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'mask_value');
        $method->setAccessible(true);

        $result = $method->invoke(null, 'user_email', 'test@example.com');
        $this->assertStringContainsString('***@', $result);
        $this->assertStringContainsString('example.com', $result);
    }

    /**
     * 测试 mask_value 对 IP 地址进行脱敏
     */
    public function test_masks_ip_values(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'mask_value');
        $method->setAccessible(true);

        $result = $method->invoke(null, 'comment_author_IP', '192.168.1.100');
        $this->assertStringContainsString('***.***.***.', $result);
    }

    /**
     * 测试 mask_value 对普通字段不脱敏
     */
    public function test_does_not_mask_non_sensitive_values(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'mask_value');
        $method->setAccessible(true);

        $this->assertEquals('Hello World', $method->invoke(null, 'post_title', 'Hello World'));
        $this->assertEquals('Some content', $method->invoke(null, 'post_content', 'Some content'));
    }

    /**
     * 测试 mask_value 处理空值
     */
    public function test_handles_empty_values(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'mask_value');
        $method->setAccessible(true);

        $this->assertEquals('', $method->invoke(null, 'user_email', ''));
        $this->assertEquals('', $method->invoke(null, 'user_pass', ''));
        $this->assertNull($method->invoke(null, 'user_email', null));
    }

    /**
     * 测试 mask_value 对长字符串截断
     */
    public function test_truncates_long_sensitive_values(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'mask_value');
        $method->setAccessible(true);

        // token 字段不是 email/ip/pass/secret/key 但仍在敏感模式中
        $result = $method->invoke(null, 'meta_value', 'abcdefghijklmnopqrstuvwxyz');
        $this->assertStringContainsString('***', $result);
    }

    /**
     * 测试代码中包含表名白名单检查
     */
    public function test_code_has_table_whitelist_check(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'is_table_allowed');
        $filename = $method->getFileName();
        $content = file_get_contents($filename);

        $this->assertStringContainsString('allowed_prefixes', $content);
        $this->assertStringContainsString('allowed_core_tables', $content);
    }

    /**
     * 测试代码包含行数限制
     */
    public function test_code_has_row_limit(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'get_table_data');
        $filename = $method->getFileName();
        $content = file_get_contents($filename);

        $this->assertStringContainsString('1000', $content);
        $this->assertStringContainsString('min(', $content);
    }

    /**
     * 测试代码包含 prepare 防止 SQL 注入
     */
    public function test_code_uses_prepare_for_sql_injection_prevention(): void {
        $method = new ReflectionMethod('MaBox_Download_SQL_Table', 'get_table_data');
        $filename = $method->getFileName();
        $content = file_get_contents($filename);

        $this->assertStringContainsString('prepare(', $content);
    }
}
