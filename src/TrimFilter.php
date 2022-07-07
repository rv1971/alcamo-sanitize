<?php

namespace alcamo\sanitize;

/// Return a (nonempty array of) trimmed nonempty string(s) or `null`
class TrimFilter extends AbstractFilter
{
    /// Return trimmed $value if nonempty string, else `null`
    public function innerFilter($value)
    {
        $value = trim($value);

        return $value != '' ? $value : null;
    }
}
