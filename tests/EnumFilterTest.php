<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class EnumFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($enums, $input, $expectedOutput)
    {
        $filter = new EnumFilter(null, $enums);

        $this->assertSame(
            $expectedOutput,
            $filter->filter($input)
        );
    }

    public function filterProvider()
    {
        return [
            [ null, null, null ],
            [ [], '', null ],
            [ 'foo', '', null ],
            [ 'foo', 'foo', 'foo' ],
            [ [ 'foo' ], 'foo', 'foo' ],
            [ [ 'foo', 'bar' ], 'foo', 'foo' ],
            [ [ 'foo', 'bar' ], 'fox', null ],
            [ [ 42, 43 ], '42', '42' ],
            [ [ 42, 43 ], 42, 42 ],
            [ [ 42, 43 ], 41, null ],
            [ [ 'foo', 'bar' ], [], null ],
            [ [ 'foo', 'bar' ], [ null, '' ], null ],
            [
                [ 'foo', 'bar++', 'baz', 42 ],
                [ 'foo', 'FOO', null, '', 'bar++', 'bar+', '42', '43' ],
                [ 'foo', 'bar++', '42' ]
            ]
        ];
    }
}
