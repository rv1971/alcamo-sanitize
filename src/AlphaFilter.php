<?php

namespace alcamo\sanitize;

/// Return a (nonempty array of) nonempty alphabetic string(s) or `null`
class AlphaFilter extends AbstractFilter
{
    /// Return $value if nonempty alphabetic string, else `null`
    public function innerFilter($value)
    {
        $value = (string)$value;

        return $value != '' && ctype_alpha($value) ? $value : null;
    }
}
