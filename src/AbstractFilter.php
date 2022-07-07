<?php

namespace alcamo\sanitize;

use alcamo\exception\DataValidationFailed;

/**
 * @brief Base class for filtering classes
 *
 * All filtering classes provided in this package
 * - return `null` when the input is an empty string
 * - remove `null` values and empty strings from iterable inputs
 * - return `null` instead of empty output arrays or sets
 */
abstract class AbstractFilter implements FilterInterface
{
    protected $flags_;
    protected $param_;

    public function __construct(?int $flags = null, $param = null)
    {
        $this->flags_ = (int)$flags;
        $this->param_ = $param;
    }

    /**
     * @brief Return a (nonempty array of) not-`null` not-empty-string value(s) filtered
     * through innerFilter(), or `null`
     *
     * @throw alcamo::exception::DataValidationFailed if innerFilter() returns
     * `null` for a value that was neither `null` nor the empty string, and
     * Sanitizer::THROW_ON_INVALID is set in the $flags given to
     * __construct().
     */
    public function filter($value)
    {
        if (is_iterable($value)) {
            /**
             * When given an iterable, remove all items which are `null` or
             * the empty string, and apply innerFilter() to each remaining
             * item, potentially removing items from the iterable. If the
             * result is empty, return `null`.
             */

            $newItems = [];

            foreach ($value as $item) {
                if (isset($item) && $item !== '') {
                    $newItem = static::innerFilter($item);

                    if (isset($newItem)) {
                        $newItems[] = $newItem;
                    } elseif ($this->flags_ & Sanitizer::THROW_ON_INVALID) {
                        throw (new DataValidationFailed())->setMessageContext(
                            [
                                'inData' => $item,
                                'inMethod' => static::class . '::innerFilter'
                            ]
                        );
                    }
                }
            }

            return $newItems ?: null;
        } elseif (isset($value) && $value !== '') {
            /**
             * When given a non-iterable which is neither `null` nor the empty
             * string, apply innerFilter() to it.
             */

            $newValue = static::innerFilter($value);

            if (isset($newValue)) {
                return $newValue;
            } elseif ($this->flags_ & Sanitizer::THROW_ON_INVALID) {
                throw (new DataValidationFailed())->setMessageContext(
                    [
                        'inData' => $value,
                        'inMethod' => static::class . '::innerFilter'
                    ]
                );
            }
        } else {
            /**
             * When given `null` or the empty string, return `null`.
             */

            return null;
        }
    }

    /// Return $value if not `null` nor the empty string, else `null`
    public function innerFilter($value)
    {
        return isset($value) && $value !== null ? $value : null;
    }
}
