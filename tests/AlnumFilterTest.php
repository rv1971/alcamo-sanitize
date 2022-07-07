<?php

namespace alcamo\sanitize;

use alcamo\exception\DataValidationFailed;
use Ds\Set;
use PHPUnit\Framework\TestCase;

class AlnumFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($input, $expectedOutput)
    {
        $filter = new AlnumFilter();

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
            [ 'foo42', 'foo42' ],
            [ 'foo-42', null ],
            [ 42, '42' ],
            [ true, '1' ],
            [ false, null ],
            [ [], null ],
            [ [ null, '+', '' ], null ],
            [ [ 'foo42', null, '', 'bar43', '#' ], [ 'foo42', 'bar43' ] ],
            [
                new Set([ '', 'bar', null, 'baz', '.' ]),
                [ 'bar', 'baz' ]
            ]
        ];
    }

    public function testExceptionPrimitive()
    {
        $filter = new AlnumFilter(Sanitizer::THROW_ON_INVALID);

        // these will not throw
        $this->assertSame(null, $filter->filter(null));

        $this->assertSame(null, $filter->filter(''));

        $this->assertSame(null, $filter->filter([]));

        $this->assertSame(null, $filter->filter([ '', null ]));

        $this->assertSame('foo', $filter->filter('foo'));

        $this->assertSame([ 'foo' ], $filter->filter([ 'foo', null, '' ]));

        // the following will throw

        $this->expectException(DataValidationFailed::class);

        $this->expectExceptionMessage(
            'in "bar=baz"'
        );

        $filter->filter('bar=baz');
    }

    public function testExceptionIterable()
    {
        $this->expectException(DataValidationFailed::class);

        $this->expectExceptionMessage(
            'in "qux++"'
        );

        (new AlnumFilter(Sanitizer::THROW_ON_INVALID))->filter(
            [ 'foo', 'qux++' ]
        );
    }
}
