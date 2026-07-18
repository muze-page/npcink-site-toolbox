<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class DevelopmentFunctionCleanupTest extends TestCase
{
    public function test_release_runtime_has_no_server_debug_logging_or_print_dump(): void
    {
        $files = array(
            'includes/class-npcink-toolbox-tool.php',
            'includes/class-npcink-toolbox-audit-logger.php',
            'admin/class-npcink-toolbox-admin.php',
            'admin/modules/loader.php',
            'admin/partials/optimize/medium/svg_support.php',
        );

        foreach ($files as $file) {
            $source = $this->source($file);
            $this->assertStringNotContainsString('error_log(', $source, $file);
            $this->assertStringNotContainsString('print_r(', $source, $file);
            $this->assertStringNotContainsString('phpcs:disable', $source, $file);
        }
    }

    public function test_unused_tool_print_method_is_removed(): void
    {
        $source = $this->source('includes/class-npcink-toolbox-tool.php');

        $this->assertStringNotContainsString('public static function p(', $source);
        $this->assertStringNotContainsString('public static function run_page_hook(', $source);
        $this->assertStringNotContainsString('public static function display_page_hook(', $source);
        $this->assertStringNotContainsString('public static function magick_admin_notice_acfs(', $source);
    }

    public function test_bootstrap_does_not_expose_a_global_runner_function(): void
    {
        $source = $this->source('npcink-site-toolbox.php');

        $this->assertStringContainsString('(new Npcink_Site_Toolbox())->run();', $source);
        $this->assertStringNotContainsString('function npcink_site_toolbox_run()', $source);
        $this->assertStringNotContainsString('function run_magick_mixture()', $source);
    }

    public function test_uninstall_does_not_keep_retired_table_cleanup(): void
    {
        $source = $this->source('uninstall.php');

        $this->assertStringNotContainsString('link_counter', $source);
        $this->assertStringNotContainsString('DROP TABLE', $source);
        $this->assertStringNotContainsString('PluginCheck.Security.DirectDB.UnescapedDBParameter', $source);
    }

    private function source(string $relativePath): string
    {
        $source = file_get_contents(dirname(__DIR__, 2) . '/' . $relativePath);
        $this->assertIsString($source);

        return $source;
    }
}
