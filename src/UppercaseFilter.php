<?php

namespace alcamo\sanitize;

/// Return a (nonempty array of) nonempty uppercase string(s) or `null`
class UppercaseFilter extends AbstractFilter
{
    /// Return $value folded to uppercase if nonempty string, else `null`
    public function innerFilter($value)
    {
        $value = strtoupper($value);

        return $value != '' ? $value : null;
    }
}
