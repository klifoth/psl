<?php

declare(strict_types=1);

namespace Psl\Type;

/**
 * @ara-return TypeInterface<i32>
 *
 * @return TypeInterface<int<-2147483648, 2147483647>>
 */
function i32(): TypeInterface
{
    /** @var Internal\I32Type $instance */
    static $instance = new Internal\I32Type();

    return $instance;
}
