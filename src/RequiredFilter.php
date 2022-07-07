<?php

namespace alcamo\sanitize;

use alcamo\exception\DataValidationFailed;

/// Throw an exception if $value is `null` or the empty string
class RequiredFilter extends AbstractFilter
{
    public function filter($value)
    {
        if (is_iterable($value)) {
            /** If $value is already an iterable, remove `null` values and
             *  empty strings. */
            $value = parent::filter($value);
        }

        if (!isset($value) || $value === '') {
            /** @throw alcamo::exception::DataValidationFailed if `null` or
             *  empty string */
            throw (new DataValidationFailed())->setMessageContext(
                [ 'extraMessage' => 'non-null value required here' ]
            );
        }

        return $value;
    }
}
