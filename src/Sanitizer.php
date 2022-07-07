<?php

namespace alcamo\sanitize;

use alcamo\exception\{
    DataValidationFailed,
    ExceptionInterface,
    InvalidEnumerator
};

/**
 * @brief Sanitize data given as key-value pairs
 *
 * Typically the sanitization rules will be stored in the class constant @ref
 * RULES in a derived class. These map keys to *rule arrays*. The sanitize()
 * method will then apply the rules to each data item.
 *
 * Each element in a rule array is either a rule name as a string value (with
 * an implicit numeric key) or a map of a rule name to a rule paremeter. The
 * latter may be of any type - number, string, array - depending on the rule.
 *
 * @note In each rule array:
 * - Order of elements matters.
 * - If the `default` rule is used, it must be the *first* element.  This
 * ensures that defaults are converted to the right type, if necessary.
 * - If a rule operating on a primitive type such as `int` is used together
 * with a rule creating an iterable such as `set`, the former must be used
 * *before* the latter. For instance, `int` converts an iterable to an
 * array of integers, and `set` converts the array of integers to a set of
 * integers. If `set` was used first, the result would be an array rather than
 * a set.
 * - If the `required` rule is used, it must be the *last* element. This
 * ensures that the sanitizer will recognize when a required data element was
 * removed because it had an illegal value.
 *
 * All built-in rules convert empty strings to `null`.
 *
 * For example:
 * - ``[ 'default' => 42, 'int' ]`` converts the value to an integer with
 * default 42.
 * - ``[ 'default' => [ 3, 4, 5 ], 'int', 'set' ]`` converts the value to a set
 * of integers. This works because `int` converts an iterable to an
 * array of integers, and `set` converts an array to a set. If the value
 * is not given, the default is the set containing the numbers 3, 4 and 5.
 * - ``[ 'enum' => [ 'foo', 'bar', 'baz' ], 'array', 'required' ]`` converts
 * the value to an array of strings, each of which must be one of `foo`, `bar`
 * or `baz`. An exception is thrown if the value is not provided.
 *
 * @sa Inspired by https://github.com/Waavi/Sanitizer
 */
class Sanitizer implements SanitizerInterface
{
    /**
     * @brief Map of rule names to filter classes
     *
     * This may be modified or extended in derived classes.
     */
    public const FILTER_CLASSES = [
        'alnum'     => AlnumFilter::class,
        'alpha'     => AlphaFilter::class,
        'array'     => ArrayFilter::class,
        'bool'      => BoolFilter::class,
        'default'   => DefaultFilter::class,
        'enum'      => EnumFilter::class,
        'explode'   => ExplodeFilter::class,
        'float'     => FloatFilter::class,
        'hex'       => HexStringFilter::class,
        'int'       => IntFilter::class,
        'lowercase' => LowercaseFilter::class,
        'max'       => MaxFilter::class,
        'min'       => MinFilter::class,
        'range'     => RangeFilter::class,
        'regexp'    => RegexpFilter::class,
        'required'  => RequiredFilter::class,
        'set'       => SetFilter::class,
        'string'    => StringFilter::class,
        'trim'      => TrimFilter::class,
        'uppercase' => UppercaseFilter::class
    ];

    /// Data suitable as input for ruleArrayMap2FilterObjectsMap()
    public const RULES = [];

    /**
     * @brief Throw if the input data is invalid in any way
     *
     * By default, invalid data is silently removed from the input. The only
     * rule that may throw even with the default flags is `required`.
     */
    public const THROW_ON_INVALID = 1;

    private $flags_; ///< int

    /// Convert a rule array to an array of FilterInterface objects
    public static function ruleArray2FilterObjects(
        array $ruleArray,
        ?int $flags = null
    ): array {
        $filterObjects = [];

        foreach ($ruleArray as $ruleKey => $ruleValue) {
            if (is_int($ruleKey)) {
                $class = static::FILTER_CLASSES[$ruleValue];

                $filterObjects[$ruleValue] = new $class($flags);
            } else {
                $class = static::FILTER_CLASSES[$ruleKey];

                $filterObjects[$ruleKey] = new $class($flags, $ruleValue);
            }
        }

        return $filterObjects;
    }

    /**
     * @brief Convert a map of rule arrays to a map of FilterInterface arrays
     *
     * @param $ruleArrayMap Map of keys to rule arrays.
     *
     * @return Array suitable as input to __construct().
     */
    public static function ruleArrayMap2FilterObjectsMap(
        iterable $ruleArrayMap,
        ?int $flags = null
    ): array {
        $ruleMap = [];

        foreach ($ruleArrayMap as $key => $ruleArray) {
            $ruleMap[$key] =
                static::ruleArray2FilterObjects($ruleArray, $flags);
        }

        return $ruleMap;
    }

    /**
     * @param $ruleArrayMap Map of keys to rule arrays.
     *
     * $flags See __construct().
     */
    public static function newFromRuleArrayMap(
        iterable $ruleArrayMap,
        ?int $flags = null
    ): self {
        return new static(
            $flags,
            static::ruleArrayMap2FilterObjectsMap($ruleArrayMap, $flags)
        );
    }

    private $filterObjectsMap_; ///< Map of keys to arrays of FilterInterface

    /**
     * @brief Construct from rule array map and @ref RULES
     *
     * @param $flags OR-combination of flags. So far, only @ref
     * THROW_ON_INVALID is suported.
     *
     * @param $filterObjectsMap Map of keys to arrays of
     * FilterInterface. Items in $filterObjectsMap override items with the
     * same key in @ref RULES.
     */
    public function __construct(
        ?int $flags = null,
        ?array $filterObjectsMap = null
    ) {
        $this->flags_ = (int)$flags;

        $this->filterObjectsMap_ = (array)$filterObjectsMap
            + static::ruleArrayMap2FilterObjectsMap(static::RULES, $flags);
    }

    public function getFilterObjectsMap(): array
    {
        return $this->filterObjectsMap_;
    }

    public function sanitize(array $data): array
    {
        /** Remove all entries whose keys are not present in
         *  $filterObjectsMap_. */
        $outputData = array_intersect_key($data, $this->filterObjectsMap_);

        if (
            $this->flags_ & self::THROW_ON_INVALID
            && count($outputData) != count($data)
        ) {
            $invalidKeys = array_diff_key($data, $this->filterObjectsMap_);

            /** @throw If @ref THROW_ON_INVALID is set in $flags,
             * alcamo::exception::InvalidEnumerator if the input contains keys
             * not present in the rules. */
            throw (new InvalidEnumerator())->setMessageContext(
                [
                    'value' => array_keys($invalidKeys),
                    'expectedOneOf' => array_keys($this->filterObjectsMap_)
                ]
            );
        }

        /** Apply all rules to the remaining items. */
        foreach ($this->filterObjectsMap_ as $key => $rules) {
            foreach ($rules as $filterObject) {
                /** Rules may create items with default values as well as
                 *  delete existing ones. */
                try {
                    $value = $filterObject->filter($outputData[$key] ?? null);
                } catch (ExceptionInterface $e) {
                    throw $e->addMessageContext([ 'inPlaces' => $key ]);
                }

                if (isset($value)) {
                    $outputData[$key] = $value;
                } else {
                    $outputData[$key] = null;
                }
            }
        }

        return $outputData;
    }
}
