<?php

namespace alcamo\sanitize;

/**
 * @brief Return a (nonempty array of) values which are less or equal to
 * $param given to AbstractFilter::__construct(), or `null`
 *
 * Comparison takes place using PHP rules without explicit cast, hence it may
 * be numeric or string comparison depending on the type of the input and of
 * $param.
 */
class MaxFilter extends AbstractFilter
{
    /// Return $value if not `null`, not-empty-string, and <= $param, else `null`
    public function innerFilter($value)
    {
        return isset($value) && $value !== '' && $value <= $this->param_
            ? $value
            : null;
    }
}
