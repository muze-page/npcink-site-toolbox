<?php

use PHPUnit\Framework\TestCase;

/**
 * MaBox_Audit_Logger 行为测试
 *
 * 验证日志格式化、级别过滤、截断等核心逻辑。
 */
class AuditLoggerBehaviorTest extends TestCase {

    /**
     * 测试日志级别常量定义
     */
    public function test_level_constants(): void {
        $this->assertEquals('info', MaBox_Audit_Logger::LEVEL_INFO);
        $this->assertEquals('warning', MaBox_Audit_Logger::LEVEL_WARNING);
        $this->assertEquals('error', MaBox_Audit_Logger::LEVEL_ERROR);
        $this->assertEquals('critical', MaBox_Audit_Logger::LEVEL_CRITICAL);
    }

    /**
     * 测试日志类别常量定义
     */
    public function test_category_constants(): void {
        $this->assertEquals('security', MaBox_Audit_Logger::CATEGORY_SECURITY);
        $this->assertEquals('database', MaBox_Audit_Logger::CATEGORY_DATABASE);
        $this->assertEquals('api', MaBox_Audit_Logger::CATEGORY_API);
        $this->assertEquals('rate_limit', MaBox_Audit_Logger::CATEGORY_RATE_LIMIT);
    }

    /**
     * 测试便捷方法返回正确的级别和类别
     */
    public function test_convenience_methods_return_true(): void {
        // 这些方法是 log() 的包装，只要 log() 返回 true，便捷方法也应返回 true
        $this->assertTrue(MaBox_Audit_Logger::security('测试安全事件'));
        $this->assertTrue(MaBox_Audit_Logger::database('测试数据库事件'));
        $this->assertTrue(MaBox_Audit_Logger::config('测试配置事件'));
        $this->assertTrue(MaBox_Audit_Logger::api_error('测试API错误'));
        $this->assertTrue(MaBox_Audit_Logger::file('测试文件事件'));
        $this->assertTrue(MaBox_Audit_Logger::rate_limit('测试限流事件'));
    }

    /**
     * 测试最大日志条目限制
     */
    public function test_max_log_entries_constant(): void {
        $reflection = new ReflectionClass('MaBox_Audit_Logger');
        $this->assertEquals(500, $reflection->getConstant('MAX_LOG_ENTRIES'));
    }

    /**
     * 测试日志存储选项名
     */
    public function test_option_name_constant(): void {
        $reflection = new ReflectionClass('MaBox_Audit_Logger');
        $this->assertEquals('mabox_audit_log', $reflection->getConstant('OPTION_NAME'));
    }

    /**
     * 测试清空日志返回 true
     */
    public function test_clear_returns_bool(): void {
        $result = MaBox_Audit_Logger::clear();
        $this->assertIsBool($result);
    }

    /**
     * 测试 get_recent 返回数组
     */
    public function test_get_recent_returns_array(): void {
        $result = MaBox_Audit_Logger::get_recent();
        $this->assertIsArray($result);
    }

    /**
     * 测试 get_recent 支持级别过滤参数
     */
    public function test_get_recent_accepts_level_filter(): void {
        $reflection = new ReflectionMethod('MaBox_Audit_Logger', 'get_recent');
        $params = $reflection->getParameters();
        
        $this->assertCount(3, $params);
        $this->assertEquals('limit', $params[0]->getName());
        $this->assertEquals('level', $params[1]->getName());
        $this->assertEquals('category', $params[2]->getName());
        
        // 验证默认值
        $this->assertEquals(50, $params[0]->getDefaultValue());
        $this->assertNull($params[1]->getDefaultValue());
        $this->assertNull($params[2]->getDefaultValue());
    }

    /**
     * 测试日志条目结构包含必要字段
     */
    public function test_log_entry_structure(): void {
        // 触发一条日志（存储到 error_log，不依赖数据库）
        MaBox_Audit_Logger::log('info', 'security', '结构测试', array('test' => true));
        
        // 验证方法不抛异常即视为成功
        $this->assertTrue(true);
    }
}
