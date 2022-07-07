<?php

namespace alcamo\sanitize;

/**
 * @brief Always return a non-empty array or `null`, wrapping a single value
 * into an array if necessary
 */
class ArrayFilter extends AbstractFilter
{
    /**
     * @brief Return $value as nonempty array of non-`null`
     * not-empty-string-elements, or `null`
     */
    public function filter($value)
    {
        if (!isset($value) || $value === '') {
            return null;
        }

        $newItems = [];

        foreach (is_iterable($value) ? $value : (array)$value as $item) {
            if (isset($item) && $item !== '') {
                $newItems[] = $item;
            }
        }

        return $newItems ?: null;
    }
}
