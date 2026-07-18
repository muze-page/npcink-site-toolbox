<?php

use PHPUnit\Framework\TestCase;

/**
 * Npcink_Toolbox_Config_Manager 行为测试
 *
 * 验证配置合并、拆分、迁移等核心逻辑。
 */
class ConfigManagerBehaviorTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();
        // 每个用例前清理静态缓存，避免测试间污染
        $reflection = new ReflectionClass('Npcink_Toolbox_Config_Manager');
        $property = $reflection->getProperty('merged_cache');
        $property->setValue(null, null);

        // 清理全局 option store
        $GLOBALS['_test_option_store'] = array();
        $GLOBALS['_test_update_option_failures'] = array();
        $GLOBALS['_test_delete_option_failures'] = array();
    }

    /**
     * 测试空配置返回空数组
     */
    public function test_empty_config_returns_empty_array(): void {
        $method = new ReflectionMethod('Npcink_Toolbox_Config_Manager', 'get_merged_config');
        $result = $method->invoke(null);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * 测试合并多模块配置
     */
    public function test_merge_multiple_modules(): void {
        $GLOBALS['_test_option_store'] = array(
            'npcink_site_toolbox_optimize' => array('enabled' => true, 'cdn' => array('enabled' => false)),
            'npcink_site_toolbox_page'     => array('comment' => array('sensitive_words' => true)),
            'npcink_site_toolbox_function' => array('maintenance' => array('enabled' => false)),
        );

        $method = new ReflectionMethod('Npcink_Toolbox_Config_Manager', 'get_merged_config');
        $result = $method->invoke(null);

        $this->assertArrayHasKey('optimize', $result);
        $this->assertArrayHasKey('page', $result);
        $this->assertArrayHasKey('function', $result);
        $this->assertTrue($result['optimize']['enabled']);
    }

    /**
     * 测试获取单个模块配置
     */
    public function test_get_single_module_config(): void {
        $GLOBALS['_test_option_store'] = array(
            'npcink_site_toolbox_optimize' => array('enabled' => true),
        );

        $method = new ReflectionMethod('Npcink_Toolbox_Config_Manager', 'get_module_config');
        
        $result = $method->invoke(null, 'optimize');
        $this->assertTrue($result['enabled']);

        $result = $method->invoke(null, 'nonexistent');
        $this->assertEmpty($result);
    }

    /**
     * 测试配置缓存（单次请求内）
     */
    public function test_config_cache_within_request(): void {
        $GLOBALS['_test_option_store'] = array('npcink_site_toolbox_optimize' => array('enabled' => true));

        $method = new ReflectionMethod('Npcink_Toolbox_Config_Manager', 'get_merged_config');
        
        // 第一次调用
        $result1 = $method->invoke(null);
        // 第二次调用应该使用缓存
        $result2 = $method->invoke(null);

        $this->assertSame($result1, $result2);
        $this->assertIsArray($result1);
    }

    public function test_same_value_is_not_treated_as_save_failure(): void {
        $current = array('enabled' => true);
        $GLOBALS['_test_option_store']['npcink_site_toolbox_optimize'] = $current;

        $result = Npcink_Toolbox_Config_Manager::save_full_config(array('optimize' => $current));

        $this->assertTrue($result['success']);
        $this->assertSame(array('optimize'), $result['saved_modules']);
    }

    public function test_cross_module_failure_rolls_back_changed_modules(): void {
        $GLOBALS['_test_option_store'] = array(
            'npcink_site_toolbox_optimize' => array('enabled' => false),
            'npcink_site_toolbox_page' => array('feature' => array('reading_progress' => false)),
        );
        $GLOBALS['_test_update_option_failures']['npcink_site_toolbox_page'] = true;

        $result = Npcink_Toolbox_Config_Manager::save_full_config(array(
            'optimize' => array('enabled' => true),
            'page' => array('feature' => array('reading_progress' => true)),
        ));

        $this->assertFalse($result['success']);
        $this->assertSame(array('page'), $result['failed_modules']);
        $this->assertTrue($result['rollback_complete']);
        $this->assertSame(array(), $result['rollback_failed_modules']);
        $this->assertSame('保存失败，已恢复为之前的设置', $result['error']);
        $this->assertSame(array('enabled' => false), $GLOBALS['_test_option_store']['npcink_site_toolbox_optimize']);
        $this->assertSame(
            array('feature' => array('reading_progress' => false)),
            $GLOBALS['_test_option_store']['npcink_site_toolbox_page']
        );
    }

    public function test_cross_module_failure_removes_newly_created_module_option(): void {
        $GLOBALS['_test_option_store'] = array(
            'npcink_site_toolbox_page' => array('feature' => array('reading_progress' => false)),
        );
        $GLOBALS['_test_update_option_failures']['npcink_site_toolbox_page'] = true;

        $result = Npcink_Toolbox_Config_Manager::save_full_config(array(
            'optimize' => array('enabled' => true),
            'page' => array('feature' => array('reading_progress' => true)),
        ));

        $this->assertFalse($result['success']);
        $this->assertArrayNotHasKey('npcink_site_toolbox_optimize', $GLOBALS['_test_option_store']);
        $this->assertTrue($result['rollback_complete']);
    }

    public function test_new_option_delete_failure_is_reported_as_unconfirmed_rollback(): void {
        $GLOBALS['_test_option_store'] = array(
            'npcink_site_toolbox_page' => array('feature' => array('reading_progress' => false)),
        );
        $GLOBALS['_test_update_option_failures']['npcink_site_toolbox_page'] = true;
        $GLOBALS['_test_delete_option_failures']['npcink_site_toolbox_optimize'] = true;

        $result = Npcink_Toolbox_Config_Manager::save_full_config(array(
            'optimize' => array('enabled' => true),
            'page' => array('feature' => array('reading_progress' => true)),
        ));

        $this->assertFalse($result['success']);
        $this->assertSame(array('page'), $result['failed_modules']);
        $this->assertFalse($result['rollback_complete']);
        $this->assertSame(array('optimize'), $result['rollback_failed_modules']);
        $this->assertStringContainsString('optimize', $result['error']);
        $this->assertStringContainsString('请重新读取并核对设置后再保存', $result['error']);
        $this->assertStringNotContainsString('已恢复为之前的设置', $result['error']);
        $this->assertSame(
            array('enabled' => true),
            $GLOBALS['_test_option_store']['npcink_site_toolbox_optimize']
        );
    }

    public function test_rollback_treats_update_option_false_as_success_when_readback_matches(): void {
        $previous = array('enabled' => false);
        $GLOBALS['_test_option_store']['npcink_site_toolbox_optimize'] = $previous;
        $GLOBALS['_test_update_option_failures']['npcink_site_toolbox_optimize'] = true;

        $result = $this->invokeRollbackFailedSave(array(
            'npcink_site_toolbox_optimize' => array(
                'module' => 'optimize',
                'previous' => $previous,
            ),
        ));

        $this->assertTrue($result['rollback_complete']);
        $this->assertSame(array(), $result['rollback_failed_modules']);
        $this->assertSame('保存失败，已恢复为之前的设置', $result['error']);
    }

    public function test_rollback_reports_actionable_error_when_readback_does_not_match(): void {
        $GLOBALS['_test_option_store']['npcink_site_toolbox_optimize'] = array('enabled' => true);
        $GLOBALS['_test_update_option_failures']['npcink_site_toolbox_optimize'] = true;

        $result = $this->invokeRollbackFailedSave(array(
            'npcink_site_toolbox_optimize' => array(
                'module' => 'optimize',
                'previous' => array('enabled' => false),
            ),
        ));

        $this->assertFalse($result['rollback_complete']);
        $this->assertSame(array('optimize'), $result['rollback_failed_modules']);
        $this->assertStringContainsString('optimize', $result['error']);
        $this->assertStringContainsString('请重新读取并核对设置后再保存', $result['error']);
        $this->assertStringNotContainsString('已恢复为之前的设置', $result['error']);
        $this->assertSame(
            array('enabled' => true),
            $GLOBALS['_test_option_store']['npcink_site_toolbox_optimize']
        );
    }

    private function invokeRollbackFailedSave(array $changed): array {
        $method = new ReflectionMethod('Npcink_Toolbox_Config_Manager', 'rollback_failed_save');
        $method->setAccessible(true);

        return $method->invoke(null, 'page', $changed, new stdClass());
    }
}
