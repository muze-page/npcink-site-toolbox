<?php

use PHPUnit\Framework\TestCase;

/**
 * MaBox_Rate_Limiter 行为测试
 *
 * 验证限流计数、封禁、重置等核心逻辑。
 */
class RateLimiterBehaviorTest extends TestCase {

    /**
     * 测试首次请求允许通过
     */
    public function test_first_request_allowed(): void {
        $reflection = new ReflectionClass('MaBox_Rate_Limiter');
        $method = $reflection->getMethod('check');

        // 使用内存模拟 transient（避免依赖 WordPress 数据库）
        $transient_store = array();
        
        // 模拟 get_transient 和 set_transient
        $this->mockTransients($transient_store);

        $result = $method->invoke(null, 'test_key', array('max_requests' => 5, 'time_window' => 60));
        $this->assertTrue($result, '首次请求应该被允许');
    }

    /**
     * 测试超过限制后触发封禁
     */
    public function test_exceeding_limit_triggers_block(): void {
        $reflection = new ReflectionClass('MaBox_Rate_Limiter');
        $method = $reflection->getMethod('check');

        $transient_store = array();
        $this->mockTransients($transient_store);

        $config = array('max_requests' => 2, 'time_window' => 60, 'block_time' => 300);

        // 第 1 次请求：允许
        $this->assertTrue($method->invoke(null, 'test_key', $config));
        // 第 2 次请求：允许（达到阈值但未超过）
        $this->assertTrue($method->invoke(null, 'test_key', $config));
        // 第 3 次请求：封禁（超过 max_requests=2）
        $this->assertFalse($method->invoke(null, 'test_key', $config), '超过限制后应该被封禁');
    }

    /**
     * 测试封禁状态持续时间内拒绝请求
     */
    public function test_blocked_state_rejects_requests(): void {
        $reflection = new ReflectionClass('MaBox_Rate_Limiter');
        $method = $reflection->getMethod('check');

        $transient_store = array();
        $this->mockTransients($transient_store);

        $config = array('max_requests' => 1, 'time_window' => 60, 'block_time' => 300);

        // 触发封禁
        $method->invoke(null, 'blocked_key', $config);
        $method->invoke(null, 'blocked_key', $config); // 超过限制

        // 封禁期间再次请求应该被拒绝
        $this->assertFalse($method->invoke(null, 'blocked_key', $config), '封禁期间应该拒绝请求');
    }

    /**
     * 测试重置功能
     */
    public function test_reset_clears_limit(): void {
        $reflection = new ReflectionClass('MaBox_Rate_Limiter');
        $check_method = $reflection->getMethod('check');
        $reset_method = $reflection->getMethod('reset');

        $transient_store = array();
        $this->mockTransients($transient_store);

        $config = array('max_requests' => 1, 'time_window' => 60, 'block_time' => 300);

        // 触发封禁
        $check_method->invoke(null, 'reset_key', $config);
        $check_method->invoke(null, 'reset_key', $config);
        $this->assertFalse($check_method->invoke(null, 'reset_key', $config));

        // 模拟删除 transient
        unset($transient_store['mabox_rate_limit_' . md5('reset_key')]);

        // 重置后应该允许
        $this->assertTrue($check_method->invoke(null, 'reset_key', $config), '重置后应该允许请求');
    }

    /**
     * 辅助方法：模拟 WordPress Transient 函数
     */
    private function mockTransients(array &$store): void {
        if (!function_exists('get_transient')) {
            function get_transient($key) {
                global $_test_transient_store;
                return $_test_transient_store[$key] ?? false;
            }
        }
        if (!function_exists('set_transient')) {
            function set_transient($key, $value, $expiration = 0) {
                global $_test_transient_store;
                $_test_transient_store[$key] = $value;
                return true;
            }
        }
        if (!function_exists('delete_transient')) {
            function delete_transient($key) {
                global $_test_transient_store;
                unset($_test_transient_store[$key]);
                return true;
            }
        }
        $GLOBALS['_test_transient_store'] = &$store;
    }
}
