<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Npcink_Toolbox_Medium_Svg_Support 单元测试
 *
 * 测试 SVG 清洗逻辑，确保危险标签和属性被正确移除
 */
class Npcink_Toolbox_Svg_Sanitization_Test extends TestCase {

    /**
     * 测试 SVG Support 类存在
     */
    public function test_class_exists(): void {
        $this->assertTrue(class_exists('Npcink_Toolbox_Medium_Svg_Support'));
    }

    /**
     * 测试 sanitize_svg_content 方法存在
     */
    public function test_sanitize_method_exists(): void {
        $this->assertTrue(method_exists('Npcink_Toolbox_Medium_Svg_Support', 'sanitize_svg_content'));
    }

    /**
     * 测试移除 <script> 标签
     */
    public function test_removes_script_tags(): void {
        $input = '<svg><script>alert("xss")</script><rect/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('<script>', $output);
        $this->assertStringNotContainsString('</script>', $output);
        $this->assertStringNotContainsString('alert', $output);
    }

    /**
     * 测试移除自闭合 <script/> 标签
     */
    public function test_removes_self_closing_script(): void {
        $input = '<svg><script src="evil.js"/><rect/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('<script', $output);
    }

    /**
     * 测试移除 <object> 标签
     */
    public function test_removes_object_tags(): void {
        $input = '<svg><object data="evil.swf"></object><rect/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('<object', $output);
    }

    /**
     * 测试移除 <embed> 标签
     */
    public function test_removes_embed_tags(): void {
        $input = '<svg><embed src="evil.swf"/><rect/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('<embed', $output);
    }

    /**
     * 测试移除 <iframe> 标签
     */
    public function test_removes_iframe_tags(): void {
        $input = '<svg><iframe src="https://evil.com"></iframe><rect/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('<iframe', $output);
    }

    /**
     * 测试移除 onclick 等事件处理器
     */
    public function test_removes_on_event_handlers(): void {
        $input = '<svg><rect onclick="alert(1)" onload="evil()"/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('onclick', $output);
        $this->assertStringNotContainsString('onload', $output);
    }

    /**
     * 测试移除 javascript: 协议
     */
    public function test_removes_javascript_protocol(): void {
        $input = '<svg><a href="javascript:alert(1)">link</a></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('javascript:', $output);
    }

    /**
     * 测试移除 vbscript: 协议
     */
    public function test_removes_vbscript_protocol(): void {
        $input = '<svg><a href="vbscript:msgbox(1)">link</a></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('vbscript:', $output);
    }

    /**
     * 测试移除 CSS expression
     */
    public function test_removes_css_expression(): void {
        $input = '<svg><rect style="width:expression(alert(1))"/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('expression(', $output);
    }

    /**
     * 测试移除 <!DOCTYPE> 声明（XXE 防护）
     */
    public function test_removes_doctype(): void {
        $input = '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"><svg></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('<!DOCTYPE', $output);
    }

    /**
     * 测试移除 <!ENTITY> 声明（XXE 防护）
     */
    public function test_removes_entity(): void {
        $input = '<!ENTITY xxe SYSTEM "file:///etc/passwd"><svg></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('<!ENTITY', $output);
    }

    /**
     * 测试保留安全的 SVG 内容
     */
    public function test_preserves_safe_svg(): void {
        $input = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect x="10" y="10" width="80" height="80" fill="blue"/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringContainsString('<svg', $output);
        $this->assertStringContainsString('<rect', $output);
        $this->assertStringContainsString('fill="blue"', $output);
    }

    /**
     * 测试移除 <form> 标签
     */
    public function test_removes_form_tags(): void {
        $input = '<svg><form action="evil.php"><input type="text"/></form></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('<form', $output);
        $this->assertStringNotContainsString('<input', $output);
    }

    /**
     * 测试移除 <link> 标签
     */
    public function test_removes_link_tags(): void {
        $input = '<svg><link href="evil.css" rel="stylesheet"/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('<link', $output);
    }

    /**
     * 测试移除 <meta> 标签
     */
    public function test_removes_meta_tags(): void {
        $input = '<svg><meta charset="utf-8"/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('<meta', $output);
    }

    /**
     * 测试移除 <base> 标签
     */
    public function test_removes_base_tags(): void {
        $input = '<svg><base href="https://evil.com/"/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('<base', $output);
    }

    public function test_removes_javascript_in_attribute_value(): void {
        $input = '<svg><a xlink:href="javascript:alert(1)">link</a></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('javascript:', $output);
    }

    public function test_removes_vbscript_in_attribute_value(): void {
        $input = '<svg><a xlink:href="vbscript:msgbox(1)">link</a></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('vbscript:', $output);
    }

    public function test_removes_expression_in_style_value(): void {
        $input = '<svg><rect style="width:expression(alert(1));fill:red"/></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('expression(', $output);
    }

    public function test_removes_javascript_in_unquoted_attr(): void {
        $input = '<svg><a href=javascript:alert(1)>link</a></svg>';
        $output = Npcink_Toolbox_Medium_Svg_Support::sanitize_svg_content($input);

        $this->assertStringNotContainsString('javascript:', $output);
    }

    public function test_upload_audit_is_not_duplicated_to_server_log(): void {
        $source = file_get_contents(dirname(__DIR__, 2) . '/admin/partials/optimize/medium/svg_support.php');
        $this->assertIsString($source);

        $this->assertStringContainsString('Npcink_Toolbox_Audit_Logger::file(', $source);
        $this->assertStringNotContainsString('error_log(', $source);
    }
}
