<?php

namespace alcamo\sanitize;

/// Split a string into an array
class ExplodeFilter extends AbstractFilter
{
    /// Return nonempty array of not-`null` not-empty-string value(s) or `null`
    public function filter($value)
    {
        if (is_iterable($value)) {
            /** If $value is already an iterable, remove `null` values and
             *  empty strings. */
            return parent::filter($value);
        }

        /** Otherwise, if not `null` nor the empty string, split using $param
         *  given to AbstractFilter::__construct() as a separator. */
        if (isset($value) && $value !== '') {
            return explode($this->param_, $value);
        } else {
            /** Otherwise return `null`. */
            return null;
        }
    }
}
