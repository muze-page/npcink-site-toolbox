<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class WordPressOrgSubmissionAssetsTest extends TestCase
{
    public function test_required_listing_assets_are_valid_png_files_within_size_limits(): void
    {
        $assets = array(
            'icon-128x128.png' => array(128, 128, 1024 * 1024),
            'icon-256x256.png' => array(256, 256, 1024 * 1024),
            'banner-772x250.png' => array(772, 250, 4 * 1024 * 1024),
            'banner-1544x500.png' => array(1544, 500, 4 * 1024 * 1024),
        );

        foreach ($assets as $filename => $contract) {
            $this->assertPngContract($filename, $contract[0], $contract[1], $contract[2]);
        }

        $screenshots = array(
            'screenshot-1.png',
            'screenshot-2.png',
            'screenshot-3.png',
        );
        $actual_screenshots = glob($this->assetsDirectory() . '/screenshot-*.png');
        $this->assertIsArray($actual_screenshots);
        $actual_screenshots = array_map('basename', $actual_screenshots);
        sort($actual_screenshots);
        $this->assertSame($screenshots, $actual_screenshots);

        foreach ($screenshots as $filename) {
            $path = $this->assetsDirectory() . '/' . $filename;
            $image = getimagesize($path);
            $this->assertIsArray($image, $filename);
            $this->assertSame(IMAGETYPE_PNG, $image[2], $filename);
            $this->assertGreaterThanOrEqual(1280, $image[0], $filename);
            $this->assertGreaterThanOrEqual(720, $image[1], $filename);
            $this->assertLessThanOrEqual(10 * 1024 * 1024, filesize($path), $filename);
        }
    }

    public function test_readme_uses_the_verified_contributor_and_matching_screenshot_captions(): void
    {
        $readme = file_get_contents($this->root() . '/readme.txt');
        $this->assertIsString($readme);
        $this->assertMatchesRegularExpression('/^Contributors: muze233$/m', $readme);
        $this->assertStringContainsString(
            "== Screenshots ==\n\n"
            . "1. Overview with live diagnostics, search health, and task-oriented navigation.\n"
            . "2. Site and Media settings with clear labels and opt-in controls.\n"
            . "3. Maintenance tools with preview-first safeguards for irreversible database cleanup.\n",
            $readme
        );
    }

    public function test_listing_assets_are_excluded_from_the_installable_plugin(): void
    {
        $rules = array_values(array_filter(array_map(
            'trim',
            preg_split('/\R/', (string) file_get_contents($this->root() . '/.distignore')) ?: array()
        ), static function (string $line): bool {
            return $line !== '' && strpos($line, '#') !== 0;
        }));

        $this->assertContains('.wordpress-org', $rules);
    }

    private function assertPngContract(string $filename, int $width, int $height, int $maximum_bytes): void
    {
        $path = $this->assetsDirectory() . '/' . $filename;
        $this->assertFileExists($path);
        $image = getimagesize($path);
        $this->assertIsArray($image, $filename);
        $this->assertSame($width, $image[0], $filename);
        $this->assertSame($height, $image[1], $filename);
        $this->assertSame(IMAGETYPE_PNG, $image[2], $filename);
        $this->assertLessThanOrEqual($maximum_bytes, filesize($path), $filename);
    }

    private function assetsDirectory(): string
    {
        return $this->root() . '/.wordpress-org';
    }

    private function root(): string
    {
        return dirname(__DIR__, 2);
    }
}
