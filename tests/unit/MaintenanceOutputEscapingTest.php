<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class MaintenanceOutputEscapingTest extends TestCase
{
    public function test_countdown_assets_are_enqueued_and_printed_in_the_document_head(): void
    {
        $template = $this->source('admin/partials/page/function/maintenance/red.php');

        $this->assertMatchesRegularExpression(
            "/wp_enqueue_style\\(\\s*'mabox-maintenance-countdown'/",
            $template
        );
        $this->assertStringContainsString('$file_url . \'countdown/style.css\'', $template);
        $this->assertMatchesRegularExpression(
            "/wp_enqueue_script\\(\\s*'mabox-maintenance-countdown-script'/",
            $template
        );
        $this->assertStringContainsString('$file_url . \'countdown/main.js\'', $template);
        $this->assertMatchesRegularExpression(
            "/<head>.*wp_print_styles\\(array\\('mabox-maintenance-responsive', 'mabox-maintenance-countdown'\\)\\).*wp_print_scripts\\('mabox-maintenance-countdown-script'\\).*<\\/head>/s",
            $template
        );

        $partial = $this->source('admin/partials/page/function/maintenance/countdown/index.php');
        $this->assertStringNotContainsString('wp_print_styles', $partial);
        $this->assertStringNotContainsString('wp_print_scripts', $partial);
        $this->assertStringNotContainsString('<link ', $partial);
        $this->assertStringNotContainsString('<script type=', $partial);
    }

    public function test_countdown_date_is_passed_as_json_encoded_inline_data(): void
    {
        $source = $this->source('admin/partials/page/function/maintenance/red.php');

        $this->assertStringContainsString('wp_json_encode((string) $countdown)', $source);
        $this->assertMatchesRegularExpression(
            "/wp_add_inline_script\\(\\s*'mabox-maintenance-countdown-script'/",
            $source
        );
        $this->assertStringContainsString("'before'", $source);
        $this->assertStringNotContainsString('<?php echo $countdown', $source);
    }

    public function test_default_template_escapes_wp_die_fields(): void
    {
        $source = $this->source('admin/partials/page/function/maintenance/default/index.php');

        $this->assertStringContainsString('esc_url($logo)', $source);
        $this->assertStringContainsString('esc_attr(self::$blogname)', $source);
        $this->assertStringContainsString('wp_kses_post($countdown_content)', $source);
        $this->assertStringContainsString('esc_html($page_title)', $source);
    }

    public function test_red_template_escapes_text_and_preserves_safe_content_markup(): void
    {
        $source = $this->source('admin/partials/page/function/maintenance/red.php');

        $this->assertStringContainsString('esc_html($page_title)', $source);
        $this->assertStringContainsString('esc_html($countdown_title)', $source);
        $this->assertStringContainsString('wp_kses_post($countdown_content)', $source);
        $this->assertMatchesRegularExpression(
            "/<head>.*wp_print_styles\\(array\\('mabox-maintenance-responsive', 'mabox-maintenance-countdown'\\)\\).*<\\/head>/s",
            $source
        );
    }

    public function test_responsive_stylesheet_uses_wordpress_enqueue_api(): void
    {
        $source = $this->source('admin/partials/page/function/maintenance_tips.php');

        $this->assertStringContainsString('self::add_responsive_css();', $source);
        $this->assertMatchesRegularExpression(
            "/wp_enqueue_style\\(\\s*'mabox-maintenance-responsive'/",
            $source
        );
        $this->assertStringContainsString('self::$url . \'responsive.css\'', $source);
        $this->assertStringNotContainsString("echo '<link", $source);
    }

    public function test_target_files_do_not_suppress_plugin_check_sniffs(): void
    {
        $files = array(
            'admin/partials/page/function/maintenance/countdown/index.php',
            'admin/partials/page/function/maintenance/default/index.php',
            'admin/partials/page/function/maintenance/red.php',
            'admin/partials/page/function/maintenance_tips.php',
        );

        foreach ($files as $file) {
            $source = $this->source($file);
            $this->assertStringNotContainsString('phpcs:ignore', $source, $file);
            $this->assertStringNotContainsString('phpcs:disable', $source, $file);
        }
    }

    private function source(string $relative_path): string
    {
        $source = file_get_contents(dirname(__DIR__, 2) . '/' . $relative_path);
        $this->assertIsString($source);

        return $source;
    }
}
