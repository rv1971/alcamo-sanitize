<?php

namespace alcamo\sanitize;

/**
 * @brief Return a (nonempty array of) booleans or `null`
 *
 * Conversion to bool is done using the PHP conversion operator.
 */
class BoolFilter extends AbstractFilter
{
    /// Return $value as bool if not `null` nor empty string, else `null`
    public function innerFilter($value)
    {
        return isset($value) && $value !== '' ? (bool)$value : null;
    }
}
