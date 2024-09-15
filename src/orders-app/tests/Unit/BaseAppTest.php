<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Base Application Test => used to see if application runs as well and assertions works (also dependency installations)
 * Note: I Usually create this test at the beggining of development phase
 */
class BaseAppTest extends TestCase {
    /**
     * Test basic arithmetic.
     *
     * @return void
     */
    public function test_basic_arithmetic() {
        $this->assertEquals(2, 1 + 1, 'One plus one should equal two');
        $this->assertTrue(10 > 5, '10 is greater than 5');
        $this->assertFalse(3 > 5, '3 is not greater than 5');
    }

    /**
     * Test basic array operations.
     *
     * @return void
     */
    public function test_array_operations() {
        $array = [1, 2, 3, 4, 5];

        // Test the array contains specific values
        $this->assertContains(3, $array);
        $this->assertCount(5, $array);

        $assocArray = ['name' => 'John', 'age' => 30];
        $this->assertArrayHasKey('name', $assocArray);
        $this->assertEquals('John', $assocArray['name']);
    }

    /**
     * Test string operations.
     *
     * @return void
     */
    public function test_string_operations() {
        $string = 'Hello, World!';

        // Basic string assertions
        $this->assertStringContainsString('World', $string);
        $this->assertStringStartsWith('Hello', $string);
        $this->assertStringEndsWith('!', $string);
        $this->assertEquals(13, strlen($string));
    }

    /**
     * Test exception handling.
     *
     * @return void
     */
    public function test_exception_handling() {
        $this->expectException(\InvalidArgumentException::class);

        // Trigger an exception
        throw new \InvalidArgumentException('This is an invalid argument.');
    }
}
