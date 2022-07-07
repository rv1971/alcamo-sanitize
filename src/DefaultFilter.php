<?php

namespace alcamo\sanitize;

/**
 * @brief Return $param given to AbstractFilter::__construct() if input is
 * `null` or empty string
 */
class DefaultFilter extends AbstractFilter
{
    public function filter($value)
    {
        if (is_iterable($value)) {
            $value = parent::filter($value);
        }

        return isset($value) && $value !== '' ? $value : $this->param_;
    }

    /// Return $value if not `null` nor the empty string, else `null`
    public function innerFilter($value)
    {
        return isset($value) && $value !== null ? $value : null;
    }
}
