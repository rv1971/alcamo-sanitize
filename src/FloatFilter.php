<?php

namespace alcamo\sanitize;

/// Return a (nonempty array of) floats or `null`
class FloatFilter extends AbstractFilter
{
    /// Return $value as float if not `null` nor empty string, else `null`
    public function innerFilter($value)
    {
        return isset($value) && $value !== '' ? (float)$value : null;
    }
}
