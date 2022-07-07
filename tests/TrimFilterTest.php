<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;

class TrimFilterTest extends TestCase
{
    /**
     * @dataProvider filterProvider
     */
    public function testFilter($input, $expectedOutput)
    {
        $filter = new TrimFilter();

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
            [ "\r\n\t  ", null ],
            [ ' fOO ', 'fOO' ],
            [ "\nFoo 42\r\t", 'Foo 42' ],
            [ 42, '42' ],
            [ true, '1' ],
            [ false, null ],
            [ [], null ],
            [ [ null, '', " \n \n  \t" ], null ],
            [
                [ " F\tO\tO\t", null, '', ' baR+   ', 32 ],
                [ "F\tO\tO", 'baR+', '32' ]
            ]
        ];
    }
}
