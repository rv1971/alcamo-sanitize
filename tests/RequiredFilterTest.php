<?php

namespace alcamo\sanitize;

use alcamo\exception\DataValidationFailed;
use Ds\Set;
use PHPUnit\Framework\TestCase;

class RequiredFilterTest extends TestCase
{
    public function testExceptionNull()
    {
        $filter = new RequiredFilter();

        $this->assertSame('bar', $filter->filter('bar'));

        $this->assertSame(42, $filter->filter(42));

        $this->assertSame(
            [ true, 'bar', 55 ],
            $filter->filter([ true, 'bar', 55, '', null ])
        );

        $this->expectException(DataValidationFailed::class);

        $filter->filter(null);
    }

    public function testExceptionEmptyString()
    {
        $filter = new RequiredFilter();

        $this->expectException(DataValidationFailed::class);

        $filter->filter('');
    }

    public function testExceptionEmptyArray()
    {
        $filter = new RequiredFilter();

        $this->expectException(DataValidationFailed::class);

        $filter->filter([ '', null ]);
    }

    public function testExceptionEmptySet()
    {
        $filter = new RequiredFilter();

        $this->expectException(DataValidationFailed::class);

        $filter->filter(new Set([ '', null ]));
    }
}
