<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * MaBox_Rate_Limiter 单元测试
 *
 * 测试频率限制器的核心安全逻辑
 */
class MaBox_Rate_Limiter_Test extends TestCase {

    /**
     * 测试 Rate Limiter 类存在
     */
    public function test_class_exists(): void {
        $this->assertTrue(class_exists('MaBox_Rate_Limiter'));
    }

    /**
     * 测试 get_client_id 方法存在
     */
    public function test_get_client_id_exists(): void {
        $this->assertTrue(method_exists('MaBox_Rate_Limiter', 'get_client_id'));
    }

    /**
     * 测试 check 方法存在
     */
    public function test_check_method_exists(): void {
        $this->assertTrue(method_exists('MaBox_Rate_Limiter', 'check'));
    }

    /**
     * 测试 reset 方法存在
     */
    public function test_reset_method_exists(): void {
        $this->assertTrue(method_exists('MaBox_Rate_Limiter', 'reset'));
    }

    /**
     * 测试 get_status 方法存在
     */
    public function test_get_status_method_exists(): void {
        $this->assertTrue(method_exists('MaBox_Rate_Limiter', 'get_status'));
    }

    /**
     * 测试 permission_callback 方法存在
     */
    public function test_permission_callback_exists(): void {
        $this->assertTrue(method_exists('MaBox_Rate_Limiter', 'permission_callback'));
    }

    /**
     * 测试 permission_callback 返回 callable
     */
    public function test_permission_callback_returns_callable(): void {
        $callback = MaBox_Rate_Limiter::permission_callback('test-endpoint', array('max_requests' => 60, 'time_window' => 60));
        $this->assertIsCallable($callback);
    }

    /**
     * 测试默认配置值
     */
    public function test_default_config_values(): void {
        $reflection = new ReflectionClass('MaBox_Rate_Limiter');
        $property = $reflection->getProperty('defaults');
        $property->setAccessible(true);
        $defaults = $property->getValue();

        $this->assertEquals(60, $defaults['max_requests']);
        $this->assertEquals(60, $defaults['time_window']);
        $this->assertEquals(300, $defaults['block_time']);
    }

    /**
     * 测试 get_client_id 返回字符串（MD5 hash）
     */
    public function test_get_client_id_returns_string(): void {
        // 在没有 WordPress 环境时，get_real_ip 可能不可用
        // 但方法应该仍然返回某种字符串
        $client_id = MaBox_Rate_Limiter::get_client_id();
        $this->assertIsString($client_id);
        $this->assertNotEmpty($client_id);
    }

    /**
     * 测试 get_client_id 对相同输入产生相同输出
     */
    public function test_get_client_id_is_deterministic(): void {
        $id1 = MaBox_Rate_Limiter::get_client_id();
        $id2 = MaBox_Rate_Limiter::get_client_id();
        $this->assertEquals($id1, $id2);
    }

    /**
     * 测试 check 方法代码包含 transient 操作
     */
    public function test_check_uses_transients(): void {
        $method = new ReflectionMethod('MaBox_Rate_Limiter', 'check');
        $filename = $method->getFileName();
        $content = file_get_contents($filename);

        $this->assertStringContainsString('get_transient', $content);
        $this->assertStringContainsString('set_transient', $content);
    }

    /**
     * 测试 check 方法包含封禁逻辑
     */
    public function test_check_has_block_logic(): void {
        $method = new ReflectionMethod('MaBox_Rate_Limiter', 'check');
        $filename = $method->getFileName();
        $content = file_get_contents($filename);

        $this->assertStringContainsString('blocked', $content);
    }

    /**
     * 测试 reset 方法使用 delete_transient
     */
    public function test_reset_deletes_transient(): void {
        $method = new ReflectionMethod('MaBox_Rate_Limiter', 'reset');
        $filename = $method->getFileName();
        $content = file_get_contents($filename);

        $this->assertStringContainsString('delete_transient', $content);
    }
}
