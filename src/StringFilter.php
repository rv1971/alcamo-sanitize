<?php

namespace alcamo\sanitize;

/// Return a (nonempty array of) nonempty string(s) or `null`
class StringFilter extends AbstractFilter
{
    /// Return $value if nonempty string, else `null`
    public function innerFilter($value)
    {
        $value = (string)$value;

        return $value != '' ? $value : null;
    }
}
