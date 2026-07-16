<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class PerformanceComplianceTest extends TestCase
{
    public function test_user_counts_use_traceable_preparation_and_wordpress_api(): void
    {
        $source = $this->source('includes/class-magick-mixture-tool.php');

        $this->assertMatchesRegularExpression(
            '/\$wpdb->get_results\(\s*\$wpdb->prepare\(/',
            $source
        );
        $this->assertStringNotContainsString('$wpdb->get_results($sql)', $source);
        $this->assertStringContainsString('$total_users = get_user_count();', $source);
        $this->assertStringNotContainsString('count_users()', $source);
        $this->assertStringNotContainsString('SELECT COUNT(ID) FROM', $source);
    }

    public function test_media_health_like_wildcards_are_prepare_parameters(): void
    {
        $source = $this->source('admin/partials/performance/media_health/index.php');

        $this->assertSame(2, substr_count($source, 'LIKE CONCAT(%s, p.guid, %s)'));
        $this->assertStringNotContainsString("CONCAT('%%', p.guid, '%%')", $source);
        $this->assertMatchesRegularExpression(
            "/'attachment',\s*'%',\s*'%',\s*'%',\s*'%'/",
            $source
        );
    }

    public function test_oss_uses_wordpress_file_deletion_api(): void
    {
        $source = $this->source('admin/partials/performance/oss/index.php');

        $this->assertStringContainsString('wp_delete_file($file);', $source);
        $this->assertSame(0, preg_match('/(?<![A-Za-z0-9_])unlink\s*\(/', $source));
    }

    private function source(string $relativePath): string
    {
        $source = file_get_contents(dirname(__DIR__, 2) . '/' . $relativePath);
        $this->assertIsString($source);

        return $source;
    }
}
