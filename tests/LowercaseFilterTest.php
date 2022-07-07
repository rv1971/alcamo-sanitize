<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class LowercaseFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($input, $expectedOutput)
    {
        $filter = new LowercaseFilter();

        $this->assertSame(
            $expectedOutput,
            $filter->filter($input)
        );
    }

    public function filterProvider()
    {
        return [
            [ null, null ],
            [ '', null ],
            [ 'fOO', 'foo' ],
            [ 'Foo42', 'foo42' ],
            [ 42, '42' ],
            [ true, '1' ],
            [ false, null ],
            [ [], null ],
            [ [ null, '' ], null ],
            [ [ 'FOO', null, '', 'baR+', 32 ], [ 'foo', 'bar+', '32' ] ]
        ];
    }
}
