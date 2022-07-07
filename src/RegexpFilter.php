<?php

namespace alcamo\sanitize;

/**
 * @brief Return a (nonempty array of) nonempty strings matching the regexp
 * $param given to AbstractFilter::__construct(), or `null`
 */
class RegexpFilter extends AbstractFilter
{
    /// Return $value if not `null`, not-empty-string, matching $param, else `null`
    public function innerFilter($value)
    {
        return (isset($value) && $value !== ''
                && preg_match($this->param_, $value))
            ? $value
            : null;
    }
}
