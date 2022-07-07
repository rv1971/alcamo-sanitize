<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class UppercaseFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($input, $expectedOutput)
    {
        $filter = new UppercaseFilter();

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
            [ 'fOO', 'FOO' ],
            [ 'Foo42', 'FOO42' ],
            [ 42, '42' ],
            [ true, '1' ],
            [ false, null ],
            [ [], null ],
            [ [ null, '' ], null ],
            [ [ 'FoO', null, '', 'baR+', 32 ], [ 'FOO', 'BAR+', '32' ] ]
        ];
    }
}
