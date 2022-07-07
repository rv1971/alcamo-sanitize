<?php

namespace alcamo\sanitize;

/// Return a (nonempty array of) nonempty alphanumeric string(s) or `null`
class AlnumFilter extends AbstractFilter
{
    /// Return $value if nonempty alphanumeric string, else `null`
    public function innerFilter($value)
    {
        $value = (string)$value;

        return $value !== '' && ctype_alnum($value) ? $value : null;
    }
}
