<?php

namespace alcamo\sanitize;

/**
 * @brief Provide sanitize()
 */
interface SanitizerInterface
{
    /**
     * @brief Transform an input array to an output array
     *
     * May do anything with the input data, including
     * - removal of items
     * - addition of items
     * - modification of item values
     */
    public function sanitize(array $data): array;
}
