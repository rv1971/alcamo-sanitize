<?php

namespace alcamo\sanitize;

use PHPUnit\Framework\TestCase;
use alcamo\exception\{DataValidationFailed, InvalidEnumerator};
use Ds\Set;

class MySanitizer extends Sanitizer
{
    public const RULES = [
        'foo-max' => [ 'max' => 42 ],
        'foo-min' => [ 'min' => 24 ],
        'foo-range' => [ 'range' => [ 'bar', 'baz' ] ],
        'foo-regexp' => [ 'regexp' => '/^(abc+)$/' ]
    ];
}

class SanitizerTest extends TestCase
{
    /**
     * @dataProvider sanitizeProvider
     */
    public function testSanitize($rules, $data, $expectedData)
    {
        $sanitizer = MySanitizer::newFromRuleArrayMap($rules);

        $this->assertEquals($expectedData, $sanitizer->sanitize($data));

        $this->assertEquals(
            $sanitizer->sanitize($data),
            $sanitizer->sanitize($sanitizer->sanitize($data))
        );
    }

    public function sanitizeProvider()
    {
        $simpleRules = [
            'foo-alnum' => [ 'alnum' ],
            'foo-alpha' => [ 'alpha' ],
            'foo-array' => [ 'array' ],
            'foo-bool' => [ 'bool' ],
            'foo-default' => [ 'default' => 'Lorem ipsum.' ],
            'foo-enum' => [ 'enum' => [ 'bar', 'baz' ,'qux' ] ],
            'foo-explode' => [ 'explode' => ',' ],
            'foo-float' => [ 'default' => 3.14, 'float' ],
            'foo-hex' => [ 'hex' ],
            'foo-int' => [ 'int' ],
            'foo-lowercase' => [ 'lowercase' ],
            'foo-set' => [ 'set' ],
            'foo-string' => [ 'string' ],
            'foo-trim' => [ 'trim' ],
            'foo-uppercase' => [ 'uppercase' ],
            'foo-int-set' => [ 'int', 'set' ]
        ];

        $complexRules = [
            'foo-max' => [ 'max' => 12 ],
            'foo-min' => [ 'min' => 1 ],
            'int-42' => [ 'default' => 42, 'int' ],
            'set-345' => [ 'default' => [ 3, 4, 5 ], 'int', 'set' ],
            'foo-bar' => [ 'enum' => [ 'foo', 'bar', 'baz' ], 'array' ],
            'set-hex' => [ 'hex', 'set' ]
        ];

        return [
           'simple' => [
               $simpleRules,
               [
                   'foo-alnum' => 'BAR7',
                   'foo-alpha' => 'FOO',
                   'foo-array' => 'FOO',
                   'foo-bool' => '1',
                   'foo-default' => 'dolor sit amet',
                   'foo-enum' => 'bar',
                   'foo-explode' => 'qux,baz,bar',
                   'foo-float' => '42',
                   'foo-hex' => "ab\t\nCD 34",
                   'foo-int' => '42',
                   'foo-lowercase' => 'LoREM IpSuM',
                   'foo-set' => 'Foo',
                   'foo-string' => 42,
                   'foo-trim' => "\t\nb a r\r  ",
                   'foo-uppercase' => 'LoREM IpSuM',
                   'foo-int-set' => '3',
                   'foo-max' => 42,
                   'foo-min' => 24,
                   'foo-range' => 'bax',
                   'foo-regexp' => 'abcccc'
               ],
               [
                   'foo-alnum' => 'BAR7',
                   'foo-alpha' => 'FOO',
                   'foo-array' => [ 'FOO' ],
                   'foo-bool' => true,
                   'foo-default' => 'dolor sit amet',
                   'foo-enum' => 'bar',
                   'foo-explode' => [ 'qux', 'baz', 'bar' ],
                   'foo-float' => 42.,
                   'foo-hex' => 'abCD34',
                   'foo-int' => 42,
                   'foo-lowercase' => 'lorem ipsum',
                   'foo-set' => new Set(['Foo']),
                   'foo-string' => '42',
                   'foo-trim' => 'b a r',
                   'foo-uppercase' => 'LOREM IPSUM',
                   'foo-int-set' => new Set([3]),
                   'foo-max' => 42,
                   'foo-min' => 24,
                   'foo-range' => 'bax',
                   'foo-regexp' => 'abcccc'
               ]
           ],
           'nulls' => [
               $simpleRules,
               [
                   'foo-array' => [],
                   'foo-string' => null,
                   'foo-bool' => null,
                   'foo-int' => null,
                   'foo-min' => ''
               ],
               [
                   'foo-alnum' => null,
                   'foo-alpha' => null,
                   'foo-array' => null,
                   'foo-bool' => null,
                   'foo-default' => 'Lorem ipsum.',
                   'foo-enum' => null,
                   'foo-explode' => null,
                   'foo-float' => 3.14,
                   'foo-hex' => null,
                   'foo-int' => null,
                   'foo-lowercase' => null,
                   'foo-set' => null,
                   'foo-string' => null,
                   'foo-trim' => null,
                   'foo-uppercase' => null,
                   'foo-int-set' => null,
                   'foo-max' => null,
                   'foo-min' => null,
                   'foo-range' => null,
                   'foo-regexp' => null
               ]
           ],
           'arrays' => [
               $simpleRules,
               [
                   'foo-alnum' => [ 'B^r', 'Bar42', 'F°o' ],
                   'foo-alpha' => [ 'Foo', 'F"o' ],
                   'foo-array' => [ 'FOO', 'BAR' ],
                   'foo-bool' => [ 1, 0 ],
                   'foo-enum' => [ 'BAR', 'bar', 'baz', 'BAZ' ],
                   'foo-explode' => [ 'qux', 'qux', 'foo' ],
                   'foo-float' => 'x3',
                   'foo-hex' => [ '1234 fe dc', 'abEF' ],
                   'foo-int' => [ '43', '44', '45' ],
                   'foo-lowercase' => [ 'Lorem', 'Ipsum' ],
                   'foo-set' => [ 'FOO', 'BAR', 'FOO' ],
                   'foo-string' => [ true, 43, 'foo' ],
                   'foo-trim' => [ ' lorem ipsum  ', 'dolor', "sit amet\n\n" ],
                   'foo-uppercase' => [ 'Lorem', 'Ipsum' ],
                   'foo-int-set' => [ 3, '5', 7, '11', 13, '17', 3, '5' ],
                   'foo-max' => [ 41, 42, 43 ],
                   'foo-min' => [ 23, 24, 25 ],
                   'foo-range' => [ 'bar', 'baz', 'qux', 'bayz', 'baz0' ],
                   'foo-regexp' => [ 'abc', 'abcc', 'abbc', 'ab', 'abcccc' ]
               ],
               [
                   'foo-alnum' => [ 'Bar42' ],
                   'foo-alpha' => [ 'Foo' ],
                   'foo-array' => [ 'FOO', 'BAR' ],
                   'foo-bool' => [ true, false ],
                   'foo-default' => 'Lorem ipsum.',
                   'foo-enum' => [ 'bar', 'baz' ],
                   'foo-explode' => [ 'qux', 'qux', 'foo' ],
                   'foo-float' => 0.,
                   'foo-hex' => [ '1234fedc', 'abEF' ],
                   'foo-int' => [ 43, 44, 45 ],
                   'foo-lowercase' => [ 'lorem', 'ipsum' ],
                   'foo-set' => new Set([ 'FOO', 'BAR' ]),
                   'foo-string' => [ '1', '43', 'foo' ],
                   'foo-trim' => [ 'lorem ipsum', 'dolor', 'sit amet' ],
                   'foo-uppercase' => [ 'LOREM', 'IPSUM' ],
                   'foo-int-set' => new Set([3, 5, 7, 11, 13, 17]),
                   'foo-max' => [ 41, 42 ],
                   'foo-min' => [ 24, 25 ],
                   'foo-range' => [ 'bar', 'baz', 'bayz' ],
                   'foo-regexp' => [ 'abc', 'abcc', 'abcccc' ]
               ]
           ],
           'complex-1' => [
               $complexRules,
               [
                   'int-42' => 7,
                   'set-345' => [ 6, '7', '8', '9' ],
                   'foo-bar' => [ 'BAR', 'foo', 'FOO' ],
                   'foo-max' => 12,
                   'foo-min' => 1
               ],
               [
                   'foo-max' => 12,
                   'foo-min' => 1,
                   'int-42' => 7,
                   'set-345' => new Set([6, 7, 8, 9]),
                   'foo-bar' => [ 'foo' ],
                   'set-hex' => null,
                   'foo-range' => null,
                   'foo-regexp' => null
               ]
           ],
           'complex-2' => [
               $complexRules,
               [
                   'foo-bar' => [ 'baz', 'baz', 'BAZ', 'foo', 'bar', 'qux' ],
                   'set-hex' => [ '12 34 56', '7890 abcd', '00', '1 1', '00' ],
                   'foo-max' => 13,
                   'foo-min' => -1
               ],
               [
                   'foo-max' => null,
                   'foo-min' => null,
                   'int-42' => 42,
                   'set-345' => new Set([3, 4, 5]),
                   'foo-bar' => [ 'baz', 'baz', 'foo', 'bar' ],
                   'set-hex' => new Set([ '123456', '7890abcd', '00', '11' ]),
                   'foo-range' => null,
                   'foo-regexp' => null
               ]
           ]
        ];
    }

    public function testSanitizeInvalidEnumerator()
    {
        $rules = [ 'foo' => [ 'string' ] ];

        $data = [ 'foo' => 'bar', 'baz' => '123', 'qux' => true ];

        $sanitizer1 = Sanitizer::newFromRuleArrayMap($rules);

        $this->assertSame(
            [ 'foo' => 'bar' ],
            $sanitizer1->sanitize($data)
        );

        $sanitizer2 = Sanitizer::newFromRuleArrayMap(
            $rules,
            Sanitizer::THROW_ON_INVALID
        );

        $this->expectException(InvalidEnumerator::class);
        $this->expectExceptionMessage(
            'Invalid value ["baz", "qux"], expected one of ["foo"]'
        );

        $sanitizer2->sanitize($data);
    }

    public function testSanitizeRequired()
    {
        $rules = [ 'foo' => [ 'required' ], 'bar' => [ 'int' ] ];

        $sanitizer = Sanitizer::newFromRuleArrayMap(
            $rules,
            Sanitizer::THROW_ON_INVALID
        );

        $sanitizer->sanitize([ 'foo' => 'bar' ]);

        $this->expectException(DataValidationFailed::class);

        $this->expectExceptionMessage(
            'Validation failed in "foo"; non-null value required here'
        );

        $sanitizer->sanitize([ 'bar' => 2 ]);
    }
}
