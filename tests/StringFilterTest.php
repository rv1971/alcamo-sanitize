<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class StringFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($input, $expectedOutput)
    {
        $filter = new StringFilter();

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
            [ 'fOO', 'fOO' ],
            [ 'Foo42', 'Foo42' ],
            [ 42, '42' ],
            [ true, '1' ],
            [ false, null ],
            [ [], null ],
            [ [ null, '' ], null ],
            [ [ 'FOO', null, '', 'baR+', 32 ], [ 'FOO', 'baR+', '32' ] ]
        ];
    }
}
