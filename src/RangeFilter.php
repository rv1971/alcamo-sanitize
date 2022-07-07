<?php

namespace alcamo\sanitize;

use alcamo\exception\DataValidationFailed;

/**
 * @brief Return a (nonempty array of) values which are in the range of
 * $param given to AbstractFilter::__construct(), or `null`
 *
 * $param must be a numerically indexed array with two values, the minimum
 * and the maximum. Comparison takes place using PHP rules without explicit
 * cast, hence it may be numeric or string comparison depending on the type of
 * the input and of $param.
 */
class RangeFilter extends AbstractFilter
{
    /**
     * @brief $param array of enumerators or a single enumerator.
     */
    public function __construct(?int $flags = null, $param = null)
    {
        if (array_keys($param) != [ 0, 1 ]) {
            throw (new DataValidationFailed())->setMessageContext(
                [
                    'inData' => $param,
                    'expectedOneOf' => 'numerically indexed array with two values'
                ]
            );
        }

        parent::__construct($flags, $param);
    }

    /**
     * @brief Return $value if not `null`, not-empty-string, >= $param[0] and
     * <= $param[1], else `null`
     */
    public function innerFilter($value)
    {
        return (isset($value) && $value !== ''
                && $value >= $this->param_[0] && $value <= $this->param_[1])
            ? $value
            : null;
    }
}
