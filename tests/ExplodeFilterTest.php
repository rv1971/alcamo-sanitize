<?php

namespace alcamo\sanitize;

use Ds\Set;
use PHPUnit\Framework\TestCase;

class ExplodeFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($separator, $input, $expectedOutput)
    {
        $filter = new ExplodeFilter(null, $separator);

        $this->assertSame(
            $expectedOutput,
            $filter->filter($input)
        );
    }

    public function filterProvider()
    {
        return [
            [ null, null, null ],
            [ ',', '', null ],
            [ ';', null, null ],
            [ ',', 'foo', [ 'foo' ] ],
            [ ',', 'foo, bar', [ 'foo', ' bar' ] ],
            [ '|', 'x |y|zz ', [ 'x ', 'y', 'zz ' ] ],
            [ '.', [], null ],
            [ '.', [ null, '', '' ], null ],
            [ ':', new Set([ null, '', 'foo' ]), [ 'foo' ] ]
        ];
    }
}
