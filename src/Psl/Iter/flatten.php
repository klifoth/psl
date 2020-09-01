<?php

declare(strict_types=1);

namespace Psl\Iter;

use Generator;
use Psl\Internal;

/**
 * Returns an iterator formed by merging the iterable elements of the
 * given iterable.
 *
 * @psalm-template Tk
 * @psalm-template Tv
 *
 * @psalm-param iterable<iterable<Tk, Tv>> $iterables
 *
 * @psalm-return Iterator<Tk, Tv>
 */
function flatten(iterable $iterables): Iterator
{
    return Internal\lazy_iterator(static function () use ($iterables): Generator {
        foreach ($iterables as $iterable) {
            foreach ($iterable as $key => $value) {
                yield $key => $value;
            }
        }
    });
}
