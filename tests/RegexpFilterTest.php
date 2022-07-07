<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class RegexpFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($regexp, $input, $expectedOutput)
    {
        $filter = new RegexpFilter(null, $regexp);

        $this->assertSame(
            $expectedOutput,
            $filter->filter($input)
        );
    }

    public function filterProvider()
    {
        return [
            [ '/.*/', null, null ],
            [ '/.*/', '', null ],
            [ '/^(bar+|baz)$/', 'barr', 'barr' ],
            [ '/^(bar+|baz)$/', 'barx', null ],
            [
                '/^(bar+|baz)$/',
                [ null, 'ba', 'bar', 'barrr', 'Baz', '', 'baz' ],
                [ 'bar', 'barrr', 'baz' ]
            ]
        ];
    }
}
