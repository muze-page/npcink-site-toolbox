<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SeoOutputEscapingTest extends TestCase
{
    public function test_meta_and_title_outputs_use_their_html_context(): void
    {
        $single = $this->source('seo_single.php');
        $this->assertStringContainsString("content=\"' . esc_attr(\$keywords) . '\"", $single);
        $this->assertStringContainsString("content=\"' . esc_attr(\$description) . '\"", $single);

        $tag = $this->source('seo_tag.php');
        $this->assertStringContainsString("content=\"' . esc_attr(\$description) . '\"", $tag);

        $category = $this->source('seo_category.php');
        $this->assertStringContainsString("'<title>' . esc_html(\$title) . '</title>'", $category);
        $this->assertStringContainsString("content=\"' . esc_attr(\$keywords) . '\"", $category);
        $this->assertStringContainsString("content=\"' . esc_attr(\$description) . '\"", $category);

        $home = $this->source('seo_home.php');
        $this->assertStringContainsString("'<title>' . esc_html(\$title) . '</title>'", $home);
        $this->assertStringContainsString("content=\"' . esc_attr(\$keywords) . '\"", $home);
        $this->assertStringContainsString("content=\"' . esc_attr(\$description) . '\"", $home);
    }

    public function test_tag_description_uses_wordpress_text_stripping(): void
    {
        $source = $this->source('seo_tag.php');

        $this->assertStringContainsString('wp_strip_all_tags(tag_description())', $source);
        $this->assertSame(0, preg_match('/(?<![A-Za-z0-9_])strip_tags\s*\(/', $source));
    }

    public function test_taxonomy_edit_fields_escape_values_and_term_names(): void
    {
        $source = $this->source('seo_category_add_meat.php');

        $this->assertStringContainsString("esc_attr(get_option('cat-title-' . \$tag->term_id))", $source);
        $this->assertStringContainsString("esc_attr(get_option('cat-words-' . \$tag->term_id))", $source);
        $this->assertSame(2, substr_count($source, 'esc_html($tag->name)'));
    }

    private function source(string $filename): string
    {
        $source = file_get_contents(
            dirname(__DIR__, 2) . '/admin/partials/function/seo/' . $filename
        );
        $this->assertIsString($source);

        return $source;
    }
}
