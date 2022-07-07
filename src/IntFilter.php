<?php

namespace alcamo\sanitize;

/// Return a (nonempty array of) ints or `null`
class IntFilter extends AbstractFilter
{
    /// Return $value as int if not `null` nor empty string, else `null`
    public function innerFilter($value)
    {
        return isset($value) && $value !== '' ? (int)$value : null;
    }
}
