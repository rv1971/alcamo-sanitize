<?php

namespace alcamo\sanitize;

/**
 * @brief Return a (nonempty array of) values contained in $param given to
 * __construct(), or `null`
 */
class EnumFilter extends AbstractFilter
{
    /**
     * @brief $param array of enumerators or a single enumerator.
     */
    public function __construct(?int $flags = null, $param = null)
    {
        parent::__construct($flags, (array)$param);
    }

    /**
     * @brief Return $value if one of the values in $param, given to
     * __construct(), else `null`
     */
    public function innerFilter($value)
    {
        return in_array($value, $this->param_) ? $value : null;
    }
}
