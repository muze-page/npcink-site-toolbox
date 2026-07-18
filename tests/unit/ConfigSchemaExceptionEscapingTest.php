<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 2) . '/includes/class-npcink-toolbox-config-schema.php';

final class ConfigSchemaExceptionEscapingTest extends TestCase
{
    public function test_dynamic_exception_messages_are_deferred_to_the_output_boundary(): void
    {
        $source = file_get_contents(
            dirname(__DIR__, 2) . '/includes/class-npcink-toolbox-config-schema.php'
        );
        $this->assertIsString($source);

        preg_match_all(
            '/throw new UnexpectedValueException\(\s*\/\/ phpcs:ignore[^\r\n]+\R\s*sprintf\(/',
            $source,
            $diagnostic_exceptions
        );

        $this->assertCount(7, $diagnostic_exceptions[0]);
        $this->assertSame(
            7,
            substr_count(
                $source,
                'phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Developer-facing diagnostic; escape only if an HTML renderer displays it.'
            )
        );
    }

    public function test_exception_message_keeps_raw_diagnostic_characters(): void
    {
        $method = new ReflectionMethod('Npcink_Toolbox_Config_Schema', 'append_search_item');
        $method->setAccessible(true);
        $search_index = array();
        $seen_ids = array();

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Search metadata for module<& must be an array');

        $method->invokeArgs(
            null,
            array(&$search_index, &$seen_ids, array('search' => 'invalid'), 'module<&')
        );
    }
}
