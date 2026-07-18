<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

if (!function_exists('absint')) {
    function absint($value)
    {
        return abs((int) $value);
    }
}
if (!function_exists('is_page')) {
    function is_page()
    {
        return !empty($GLOBALS['_test_is_page']);
    }
}
if (!function_exists('get_the_ID')) {
    function get_the_ID()
    {
        return $GLOBALS['_test_post_id'] ?? 0;
    }
}
if (!function_exists('in_category')) {
    function in_category($category_ids)
    {
        return (bool) array_intersect(
            array_map('intval', (array) $category_ids),
            array_map('intval', $GLOBALS['_test_category_ids'] ?? array())
        );
    }
}
if (!function_exists('get_the_tags')) {
    function get_the_tags()
    {
        return $GLOBALS['_test_post_tags'] ?? false;
    }
}
if (!function_exists('is_user_logged_in')) {
    function is_user_logged_in()
    {
        return !empty($GLOBALS['_test_mabox_logged_in']);
    }
}

require_once dirname(__DIR__, 2) . '/includes/interface-mabox-module.php';
require_once dirname(__DIR__, 2) . '/includes/class-magick-helpers.php';
require_once dirname(__DIR__, 2) . '/admin/partials/page/jurisdiction/hide_page.php';
require_once dirname(__DIR__, 2) . '/admin/partials/page/jurisdiction/hide_tag.php';
require_once dirname(__DIR__, 2) . '/admin/partials/page/jurisdiction/hide_category.php';

final class JurisdictionContentRuntimeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $GLOBALS['_test_is_page'] = false;
        $GLOBALS['_test_post_id'] = 0;
        $GLOBALS['_test_category_ids'] = array();
        $GLOBALS['_test_post_tags'] = false;
        $GLOBALS['_test_mabox_logged_in'] = false;
    }

    public function test_anonymous_page_receives_a_server_rendered_sanitized_notice(): void
    {
        $GLOBALS['_test_is_page'] = true;
        $GLOBALS['_test_post_id'] = 42;
        $this->setStaticProperty('MaBox_Page_Hide_Page', 'id_array', array('42'));
        $this->setStaticProperty('MaBox_Page_Hide_Page', 'tip_content', '<strong>请登录</strong><script>alert(1)</script>');

        $output = MaBox_Page_Hide_Page::restrict_content_for_specific_categories('<p>私密正文</p>');

        $this->assertSame('<strong>请登录</strong>alert(1)', $output);
    }

    public function test_anonymous_tag_and_category_receive_the_same_notice_contract(): void
    {
        $GLOBALS['_test_post_tags'] = array((object) array('term_id' => 7));
        $this->setStaticProperty('MaBox_Page_Hide_Tag', 'id_array', array(7));
        $this->setStaticProperty('MaBox_Page_Hide_Tag', 'tip_content', '<em>登录后查看</em>');
        $this->assertSame(
            '<em>登录后查看</em>',
            MaBox_Page_Hide_Tag::restrict_content_for_specific_tags('<p>私密正文</p>')
        );

        $GLOBALS['_test_category_ids'] = array(9);
        $this->setStaticProperty('MaBox_Page_Hide_Category', 'id_array', array(9));
        $this->setStaticProperty('MaBox_Page_Hide_Category', 'tip_content', '<em>登录后查看</em>');
        $this->assertSame(
            '<em>登录后查看</em>',
            MaBox_Page_Hide_Category::restrict_content_for_specific_categories('<p>私密正文</p>')
        );
    }

    public function test_logged_in_reader_keeps_original_content(): void
    {
        $GLOBALS['_test_mabox_logged_in'] = true;
        $GLOBALS['_test_is_page'] = true;
        $GLOBALS['_test_post_id'] = 42;
        $this->setStaticProperty('MaBox_Page_Hide_Page', 'id_array', array(42));
        $this->setStaticProperty('MaBox_Page_Hide_Page', 'tip_content', '请登录');

        $this->assertSame(
            '<p>完整正文</p>',
            MaBox_Page_Hide_Page::restrict_content_for_specific_categories('<p>完整正文</p>')
        );
    }

    public function test_jurisdiction_notice_allows_safe_html_without_frontend_dom_rewrites(): void
    {
        $schema = MaBox_Config_Schema::get_schema();
        $this->assertSame('wp_kses_post', $schema['page']['jurisdiction']['tip_content']['sanitize']);

        foreach (array('hide_page.php', 'hide_tag.php', 'hide_category.php') as $file) {
            $source = file_get_contents(dirname(__DIR__, 2) . '/admin/partials/page/jurisdiction/' . $file);
            $this->assertIsString($source);
            $this->assertStringNotContainsString('wp_add_inline_script', $source, $file);
            $this->assertStringNotContainsString('entryContent.innerHTML', $source, $file);
        }
    }

    private function setStaticProperty(string $class, string $property, $value): void
    {
        $reflection = new ReflectionProperty($class, $property);
        $reflection->setAccessible(true);
        $reflection->setValue(null, $value);
    }
}
