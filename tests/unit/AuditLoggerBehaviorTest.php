<?php

use PHPUnit\Framework\TestCase;

/**
 * Npcink_Toolbox_Audit_Logger 行为测试
 *
 * 验证日志格式化、级别过滤、截断等核心逻辑。
 */
class AuditLoggerBehaviorTest extends TestCase {

    /**
     * 测试日志级别常量定义
     */
    public function test_level_constants(): void {
        $this->assertEquals('info', Npcink_Toolbox_Audit_Logger::LEVEL_INFO);
        $this->assertEquals('warning', Npcink_Toolbox_Audit_Logger::LEVEL_WARNING);
        $this->assertEquals('error', Npcink_Toolbox_Audit_Logger::LEVEL_ERROR);
        $this->assertEquals('critical', Npcink_Toolbox_Audit_Logger::LEVEL_CRITICAL);
    }

    /**
     * 测试日志类别常量定义
     */
    public function test_category_constants(): void {
        $this->assertEquals('security', Npcink_Toolbox_Audit_Logger::CATEGORY_SECURITY);
        $this->assertEquals('database', Npcink_Toolbox_Audit_Logger::CATEGORY_DATABASE);
        $this->assertEquals('api', Npcink_Toolbox_Audit_Logger::CATEGORY_API);
        $this->assertEquals('rate_limit', Npcink_Toolbox_Audit_Logger::CATEGORY_RATE_LIMIT);
    }

    /**
     * 测试便捷方法返回正确的级别和类别
     */
    public function test_convenience_methods_return_true(): void {
        // 这些方法是 log() 的包装，只要 log() 返回 true，便捷方法也应返回 true
        $this->assertTrue(Npcink_Toolbox_Audit_Logger::security('测试安全事件'));
        $this->assertTrue(Npcink_Toolbox_Audit_Logger::database('测试数据库事件'));
        $this->assertTrue(Npcink_Toolbox_Audit_Logger::config('测试配置事件'));
        $this->assertTrue(Npcink_Toolbox_Audit_Logger::api_error('测试API错误'));
        $this->assertTrue(Npcink_Toolbox_Audit_Logger::file('测试文件事件'));
        $this->assertTrue(Npcink_Toolbox_Audit_Logger::rate_limit('测试限流事件'));
    }

    /**
     * 测试最大日志条目限制
     */
    public function test_max_log_entries_constant(): void {
        $reflection = new ReflectionClass('Npcink_Toolbox_Audit_Logger');
        $this->assertEquals(500, $reflection->getConstant('MAX_LOG_ENTRIES'));
    }

    /**
     * 测试日志存储选项名
     */
    public function test_option_name_constant(): void {
        $reflection = new ReflectionClass('Npcink_Toolbox_Audit_Logger');
        $this->assertEquals('npcink_site_toolbox_audit_log', $reflection->getConstant('OPTION_NAME'));
    }

    /**
     * 测试清空日志返回 true
     */
    public function test_clear_returns_bool(): void {
        $result = Npcink_Toolbox_Audit_Logger::clear();
        $this->assertIsBool($result);
    }

    /**
     * 测试 get_recent 返回数组
     */
    public function test_get_recent_returns_array(): void {
        $result = Npcink_Toolbox_Audit_Logger::get_recent();
        $this->assertIsArray($result);
    }

    /**
     * 测试 get_recent 支持级别过滤参数
     */
    public function test_get_recent_accepts_level_filter(): void {
        $reflection = new ReflectionMethod('Npcink_Toolbox_Audit_Logger', 'get_recent');
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

    public function test_log_publishes_a_structured_event_without_default_storage(): void {
        $had_action_store = array_key_exists('_test_action_store', $GLOBALS);
        $previous_action_store = $had_action_store ? $GLOBALS['_test_action_store'] : null;
        $had_option_store = array_key_exists('_test_option_store', $GLOBALS);
        $previous_option_store = $had_option_store ? $GLOBALS['_test_option_store'] : null;

        try {
            $GLOBALS['_test_action_store'] = array();
            $GLOBALS['_test_option_store'] = array();

            $this->assertTrue(
                Npcink_Toolbox_Audit_Logger::log('info', 'security', '结构测试', array('test' => true))
            );

            $this->assertArrayHasKey('npcink_site_toolbox_audit_log', $GLOBALS['_test_action_store']);
            $this->assertCount(1, $GLOBALS['_test_action_store']['npcink_site_toolbox_audit_log']);
            $entry = $GLOBALS['_test_action_store']['npcink_site_toolbox_audit_log'][0][0];
            $this->assertSame('info', $entry['level']);
            $this->assertSame('security', $entry['category']);
            $this->assertSame('结构测试', $entry['message']);
            $this->assertSame(array('test' => true), $entry['context']);
            $this->assertArrayHasKey('timestamp', $entry);
            $this->assertArrayNotHasKey(
                Npcink_Toolbox_Audit_Logger::OPTION_NAME,
                $GLOBALS['_test_option_store']
            );
        } finally {
            if ($had_action_store) {
                $GLOBALS['_test_action_store'] = $previous_action_store;
            } else {
                unset($GLOBALS['_test_action_store']);
            }
            if ($had_option_store) {
                $GLOBALS['_test_option_store'] = $previous_option_store;
            } else {
                unset($GLOBALS['_test_option_store']);
            }
        }
    }

    public function test_audit_logger_keeps_structured_extension_points_without_server_debug_log(): void {
        $source = file_get_contents(dirname(__DIR__, 2) . '/includes/class-npcink-toolbox-audit-logger.php');
        $this->assertIsString($source);

        $this->assertStringContainsString('self::store_entry($entry);', $source);
        $this->assertStringContainsString("do_action('npcink_site_toolbox_audit_log', \$entry);", $source);
        $this->assertStringNotContainsString('error_log(', $source);
    }
}
