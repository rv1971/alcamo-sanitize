<?php

namespace alcamo\sanitize;

/// Return a (nonempty array of) nonempty lowercase string(s) or `null`
class LowercaseFilter extends AbstractFilter
{
    /// Return $value folded to lowercase if nonempty string, else `null`
    public function innerFilter($value)
    {
        $value = strtolower($value);

        return $value != '' ? $value : null;
    }
}
