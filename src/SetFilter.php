<?php

namespace alcamo\sanitize;

use Ds\Set;

/**
 * @brief Always return a non-empty Set or `null`, wrapping a single value
 * into a Set if necessary
 */
class SetFilter extends AbstractFilter
{
    /// Return $value as nonempty set of non-null elements or `null`
    public function filter($value)
    {
        if (!isset($value) || $value === '') {
            return null;
        }

        if (!($value instanceof Set)) {
            $set = new Set();

            foreach (is_iterable($value) ? $value : (array)$value as $item) {
                if (isset($item) && $item !== '') {
                    $set->add($item);
                }
            }
        } else {
            $set = $value;
        }

        return $set->isEmpty() ? null : $set;
    }
}
