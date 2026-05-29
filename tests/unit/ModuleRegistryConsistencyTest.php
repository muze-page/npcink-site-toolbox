<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ModuleRegistryConsistency_Test extends TestCase {

    private static $plugin_dir;

    public static function setUpBeforeClass(): void {
        self::$plugin_dir = dirname(__DIR__, 2);
    }

    public function test_registry_module_files_exist(): void {
        $registry = MaBox_Module_Loader::get_registry();
        $partials_dir = self::$plugin_dir . '/admin/partials/';

        foreach ($registry as $module_id => $meta) {
            $file = $partials_dir . $meta['file'];
            $this->assertFileExists(
                $file,
                "Module '$module_id' file does not exist at: {$meta['file']}"
            );
        }
    }

    public function test_no_escape_module_file_exists(): void {
        $file = self::$plugin_dir . '/admin/partials/optimize/site/no_escape.php';
        $this->assertFileExists($file);
    }

    public function test_no_escape_class_exists(): void {
        require_once self::$plugin_dir . '/admin/partials/optimize/site/no_escape.php';
        $this->assertTrue(class_exists('MaBox_No_Escape'));
    }

    public function test_no_escape_implements_interface(): void {
        $this->assertTrue(
            is_subclass_of('MaBox_No_Escape', 'MaBox_Module_Interface'),
            'MaBox_No_Escape should implement MaBox_Module_Interface'
        );
    }

    public function test_no_escape_has_run_method(): void {
        $this->assertTrue(method_exists('MaBox_No_Escape', 'run'));
    }

    public function test_h5_main_removed_from_registry(): void {
        $registry = MaBox_Module_Loader::get_registry();
        $this->assertArrayNotHasKey('h5.main', $registry);
    }

    public function test_function_b2_removed_from_registry(): void {
        $registry = MaBox_Module_Loader::get_registry();
        $this->assertArrayNotHasKey('function.b2', $registry);
    }

    public function test_h5_main_removed_from_tiers(): void {
        $tiers = MaBox_Module_Loader::get_tiers();
        foreach ($tiers as $tier => $modules) {
            $this->assertNotContains('h5.main', $modules, "h5.main should not be in tier '$tier'");
        }
    }

    public function test_function_b2_removed_from_tiers(): void {
        $tiers = MaBox_Module_Loader::get_tiers();
        foreach ($tiers as $tier => $modules) {
            $this->assertNotContains('function.b2', $modules, "function.b2 should not be in tier '$tier'");
        }
    }

    public function test_h5_php_file_deleted(): void {
        $file = self::$plugin_dir . '/admin/partials/h5.php';
        $this->assertFileDoesNotExist($file);
    }

    public function test_b2_directory_deleted(): void {
        $dir = self::$plugin_dir . '/admin/partials/function/b2';
        $this->assertDirectoryDoesNotExist($dir);
    }

    public function test_jvectormap_files_deleted(): void {
        $map_dir = self::$plugin_dir . '/admin/partials/shortcode/pendant/merc_map/';
        $this->assertFileDoesNotExist($map_dir . 'jquery-jvectormap-1.2.2.min.js');
        $this->assertFileDoesNotExist($map_dir . 'jquery-jvectormap-cn-merc-en.js');
        $this->assertFileDoesNotExist($map_dir . 'jquery-jvectormap-1.2.2.css');
    }

    public function test_maintenance_deleted_templates_absent(): void {
        $maintenance_dir = self::$plugin_dir . '/admin/partials/page/function/maintenance/';
        $this->assertDirectoryDoesNotExist($maintenance_dir . 'purple');
        $this->assertDirectoryDoesNotExist($maintenance_dir . 'lighting');
        $this->assertDirectoryDoesNotExist($maintenance_dir . 'masking');
        $this->assertDirectoryDoesNotExist($maintenance_dir . 'rotate');
    }

    public function test_maintenance_kept_templates_present(): void {
        $maintenance_dir = self::$plugin_dir . '/admin/partials/page/function/maintenance/';
        $this->assertDirectoryExists($maintenance_dir . 'default');
        $this->assertFileExists($maintenance_dir . 'red.php');
    }

    public function test_merc_map_shortcode_handler_exists(): void {
        $file = self::$plugin_dir . '/admin/partials/shortcode/pendant/merc_map/index.php';
        $this->assertFileExists($file);
    }

    public function test_merc_map_implements_interface(): void {
        require_once self::$plugin_dir . '/admin/partials/shortcode/pendant/merc_map/index.php';
        $this->assertTrue(
            is_subclass_of('MaBox_ShortCode_Merc_Map', 'MaBox_Module_Interface'),
            'MaBox_ShortCode_Merc_Map should implement MaBox_Module_Interface'
        );
    }

    public function test_schema_has_no_h5_branch(): void {
        $schema = MaBox_Config_Schema::get_schema();
        $this->assertArrayNotHasKey('h5', $schema);
    }

    public function test_schema_has_no_b2_branch(): void {
        $schema = MaBox_Config_Schema::get_schema();
        $this->assertIsArray($schema['function']);
        $this->assertArrayNotHasKey('b2', $schema['function']);
    }

    public function test_config_manager_has_no_h5_mapping(): void {
        $map = MaBox_Config_Manager::get_module_map();
        $this->assertArrayNotHasKey('h5', $map);
    }

    public function test_merc_map_local_echarts_exists(): void {
        $assets_dir = self::$plugin_dir . '/admin/partials/shortcode/pendant/merc_map/assets/';
        $this->assertFileExists($assets_dir . 'echarts.min.js');
    }

    public function test_merc_map_local_china_geojson_exists(): void {
        $assets_dir = self::$plugin_dir . '/admin/partials/shortcode/pendant/merc_map/assets/';
        $this->assertFileExists($assets_dir . 'china.json');
    }

    public function test_merc_map_no_cdn_china_js_reference(): void {
        $file = self::$plugin_dir . '/admin/partials/shortcode/pendant/merc_map/index.php';
        $content = file_get_contents($file);
        $this->assertStringNotContainsString('echarts@6/map/js/china.js', $content);
        $this->assertStringNotContainsString('jquery-jvectormap', $content);
    }

    public function test_merc_map_uses_local_assets(): void {
        $file = self::$plugin_dir . '/admin/partials/shortcode/pendant/merc_map/index.php';
        $content = file_get_contents($file);
        $this->assertStringContainsString("self::\$assets_url . 'echarts.min.js'", $content);
        $this->assertStringContainsString('echarts.registerMap', $content);
    }

    public function test_merc_map_validates_coordinates(): void {
        $file = self::$plugin_dir . '/admin/partials/shortcode/pendant/merc_map/index.php';
        $content = file_get_contents($file);
        $this->assertStringContainsString('lat >= -90 && lat <= 90', $content);
        $this->assertStringContainsString('lng >= -180 && lng <= 180', $content);
    }

    public function test_no_escape_no_global_the_title_filter(): void {
        $file = self::$plugin_dir . '/admin/partials/optimize/site/no_escape.php';
        $content = file_get_contents($file);
        $this->assertStringNotContainsString("add_filter('the_title'", $content);
        $this->assertStringContainsString("add_filter('document_title_parts'", $content);
    }

    public function test_census_single_no_b2_div_id(): void {
        $file = self::$plugin_dir . '/admin/partials/function/auxiliary/census-single.php';
        $content = file_get_contents($file);
        $this->assertStringNotContainsString('MaBox_b2_shop_count', $content);
    }

    public function test_vite_count_dist_exists(): void {
        $dist_dir = self::$plugin_dir . '/vite/count/dist/';
        $this->assertFileExists($dist_dir . 'index.css');
        $this->assertFileExists($dist_dir . 'index.js');
    }

    private static function removedP0Modules(): array {
        return [
            'page.click_effect', 'page.screen_hair', 'page.lantern',
            'page.pixel_chicken', 'page.completed_book', 'page.bottom_effect',
            'page.background_effect', 'template.main', 'template.static',
            'template.trends',
        ];
    }

    private static function removedP1Modules(): array {
        return [
            'page.ticket', 'page.diary', 'services.main', 'feedback.main',
            'function.wx_xcx_link', 'function.download_sql_table',
            'page.front_debug', 'page.article_rating',
        ];
    }

    public function test_p0_modules_removed_from_registry(): void {
        $registry = MaBox_Module_Loader::get_registry();
        foreach (self::removedP0Modules() as $module_id) {
            $this->assertArrayNotHasKey($module_id, $registry, "$module_id should not be in registry");
        }
    }

    public function test_p1_modules_removed_from_registry(): void {
        $registry = MaBox_Module_Loader::get_registry();
        foreach (self::removedP1Modules() as $module_id) {
            $this->assertArrayNotHasKey($module_id, $registry, "$module_id should not be in registry");
        }
    }

    public function test_p0_modules_removed_from_tiers(): void {
        $tiers = MaBox_Module_Loader::get_tiers();
        foreach (self::removedP0Modules() as $module_id) {
            foreach ($tiers as $tier => $modules) {
                $this->assertNotContains($module_id, $modules, "$module_id should not be in tier '$tier'");
            }
        }
    }

    public function test_p1_modules_removed_from_tiers(): void {
        $tiers = MaBox_Module_Loader::get_tiers();
        foreach (self::removedP1Modules() as $module_id) {
            foreach ($tiers as $tier => $modules) {
                $this->assertNotContains($module_id, $modules, "$module_id should not be in tier '$tier'");
            }
        }
    }

    public function test_p0_module_files_deleted(): void {
        $partials = self::$plugin_dir . '/admin/partials/';
        $deleted_paths = [
            'page/exterior/screen_hair',
            'page/exterior/lantern',
            'page/exterior/pixel_chicken',
            'page/exterior/click_effect',
            'page/exterior/bottom_effect',
            'page/exterior/background_effect',
            'page/exterior/completed_book.php',
            'template',
        ];
        foreach ($deleted_paths as $path) {
            $full = $partials . $path;
            $this->assertFileDoesNotExist($full, "$path should be deleted");
        }
    }

    public function test_p1_module_files_deleted(): void {
        $partials = self::$plugin_dir . '/admin/partials/';
        $deleted_paths = [
            'page/ticket',
            'page/diary',
            'services',
            'feedback',
            'function/wx_xcx_link',
            'function/download-sql-table.php',
            'page/jurisdiction/front_debug.php',
            'page/function/article_rating.php',
            'page/function/article_rating.js',
        ];
        foreach ($deleted_paths as $path) {
            $full = $partials . $path;
            $this->assertFileDoesNotExist($full, "$path should be deleted");
        }
    }

    public function test_schema_has_no_removed_branches(): void {
        $schema = MaBox_Config_Schema::get_schema();
        $this->assertArrayNotHasKey('template', $schema);
        $this->assertArrayNotHasKey('services', $schema);
        $this->assertArrayNotHasKey('feedback', $schema);
        $this->assertArrayNotHasKey('wx_xcx', $schema['function']);
    }

    public function test_schema_page_feature_has_no_removed_fields(): void {
        $schema = MaBox_Config_Schema::get_schema();
        $feature = $schema['page']['feature'];
        $removed_feature_fields = ['particle', 'screen_hair', 'lantern', 'lantern_left', 'lantern_right', 'pixel_chicken', 'past_books', 'bottom_effect', 'background_effect'];
        foreach ($removed_feature_fields as $field) {
            $this->assertArrayNotHasKey($field, $feature, "page.feature.$field should not exist in schema");
        }
    }

    public function test_schema_page_function_has_no_removed_fields(): void {
        $schema = MaBox_Config_Schema::get_schema();
        $func = $schema['page']['function'];
        $this->assertArrayNotHasKey('article_rating', $func);
        $this->assertArrayNotHasKey('ticket', $func);
        $this->assertArrayNotHasKey('diary', $func);
    }

    public function test_schema_page_jurisdiction_has_no_front_debug(): void {
        $schema = MaBox_Config_Schema::get_schema();
        $this->assertArrayNotHasKey('front_debug', $schema['page']['jurisdiction']);
    }

    public function test_config_manager_has_no_removed_mappings(): void {
        $map = MaBox_Config_Manager::get_module_map();
        $this->assertArrayNotHasKey('template', $map);
        $this->assertArrayNotHasKey('services', $map);
        $this->assertArrayNotHasKey('feedback', $map);
    }

    public function test_readme_has_no_removed_feature_references(): void {
        $readme = file_get_contents(self::$plugin_dir . '/README.md');
        $removed = ['工单系统', '用户反馈', '增值服务', '点击特效', '背景特效'];
        foreach ($removed as $term) {
            $this->assertStringNotContainsString($term, $readme, "README.md should not reference removed feature '$term'");
        }
    }

    public function test_readme_txt_has_no_removed_feature_references(): void {
        $readme = file_get_contents(self::$plugin_dir . '/readme.txt');
        $removed = ['增值服务', '用户反馈'];
        foreach ($removed as $term) {
            $this->assertStringNotContainsString($term, $readme, "readme.txt should not reference removed feature '$term'");
        }
    }

    public function test_feature_list_has_no_removed_feature_references(): void {
        $file = self::$plugin_dir . '/功能清单.md';
        if (!file_exists($file)) {
            $this->markTestSkipped('功能清单.md not found');
        }
        $content = file_get_contents($file);
        $removed = ['点击特效', '背景特效', '页面模板', '小程序跳转'];
        foreach ($removed as $term) {
            $this->assertStringNotContainsString($term, $content, "功能清单.md should not reference removed feature '$term'");
        }
    }

    public function test_docs_site_config_has_no_page_templates_nav(): void {
        $config = file_get_contents(self::$plugin_dir . '/docs-site/.vitepress/config.ts');
        $this->assertStringNotContainsString('page-templates', $config);
        $this->assertStringNotContainsString('页面模板', $config);
    }

    public function test_docs_site_overview_has_no_page_templates(): void {
        $overview = file_get_contents(self::$plugin_dir . '/docs-site/features/overview.md');
        $this->assertStringNotContainsString('页面模板', $overview);
        $this->assertStringNotContainsString('page-templates', $overview);
    }

    public function test_docs_site_architecture_has_no_removed_dirs(): void {
        $arch = file_get_contents(self::$plugin_dir . '/docs-site/guide/architecture.md');
        $this->assertStringNotContainsString('feedback/', $arch);
        $this->assertStringNotContainsString('services/', $arch);
    }

    public function test_docs_site_config_recovery_has_no_removed_modules(): void {
        $recovery = file_get_contents(self::$plugin_dir . '/docs-site/guide/config-recovery.md');
        $this->assertStringNotContainsString('增值服务', $recovery);
        $this->assertStringNotContainsString('用户反馈', $recovery);
        $this->assertStringNotContainsString('services', $recovery);
        $this->assertStringNotContainsString('feedback', $recovery);
    }

    public function test_docs_site_page_templates_dir_deleted(): void {
        $dir = self::$plugin_dir . '/docs-site/features/page-templates';
        $this->assertDirectoryDoesNotExist($dir);
    }

    public function test_frontend_api_has_no_feedback_api(): void {
        $api = file_get_contents(self::$plugin_dir . '/vite/admin/src/api/index.ts');
        $this->assertStringNotContainsString('feedbackApi', $api);
        $this->assertStringNotContainsString('/feedback/', $api);
    }

    public function test_frontend_css_has_no_template_row_styles(): void {
        $css = file_get_contents(self::$plugin_dir . '/vite/admin/src/App.css');
        $this->assertStringNotContainsString('mabox-template-row', $css);
    }

    public function test_frontend_assets_template_dir_deleted(): void {
        $dir = self::$plugin_dir . '/vite/admin/src/assets/template';
        $this->assertDirectoryDoesNotExist($dir);
    }
}