<?php

namespace alcamo\sanitize;

/**
 * @brief Return a (nonempty array of) nonempty hexadecimal string(s) or `null`
 *
 * The result strings are only guaranteed to be made of hexadecimal
 * digits. They are neither guaranteed to have an even number of digits nor to
 * be folded to uppercase or lowercase. Case folding can be achieved using
 * LowercaseFilter and UppercaseFilter when needed.
 */
class HexStringFilter extends AbstractFilter
{
    /// Return $value with whitespace removed if nonempty hex string, else `null`
    public function innerFilter($value)
    {
        $value = preg_replace('/\s+/', '', $value);

        return $value != '' && ctype_xdigit($value) ? $value : null;
    }
}
