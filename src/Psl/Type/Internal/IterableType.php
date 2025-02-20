<?php

declare(strict_types=1);

namespace Psl\Type\Internal;

use Psl\Iter;
use Psl\Str;
use Psl\Type;
use Psl\Type\Exception\AssertException;
use Psl\Type\Exception\CoercionException;
use Throwable;

use function is_iterable;

/**
 * @template Tk
 * @template Tv
 *
 * @extends Type\Type<iterable<Tk, Tv>>
 *
 * @internal
 */
final readonly class IterableType extends Type\Type
{
    /**
     * @psalm-mutation-free
     *
     * @param Type\TypeInterface<Tk> $key_type
     * @param Type\TypeInterface<Tv> $value_type
     */
    public function __construct(
        private readonly Type\TypeInterface $key_type,
        private readonly Type\TypeInterface $value_type
    ) {
    }

    /**
     * @throws CoercionException
     *
     * @return iterable<Tk, Tv>
     */
    public function coerce(mixed $value): iterable
    {
        if (is_iterable($value)) {
            /** @var Type\Type<Tk> $key_type */
            $key_type = $this->key_type;
            /** @var Type\Type<Tv> $value_type_speec */
            $value_type = $this->value_type;

            /** @var list<array{Tk, Tv}> $entries */
            $entries = [];

            $k = $v = null;
            $trying_key = true;
            $iterating = true;

            try {
                /**
                 * @var Tk $k
                 * @var Tv $v
                 */
                foreach ($value as $k => $v) {
                    $iterating = false;
                    $trying_key = true;
                    $k_result = $key_type->coerce($k);
                    $trying_key = false;
                    $v_result = $value_type->coerce($v);

                    $entries[] = [$k_result, $v_result];
                    $iterating = true;
                }
            } catch (Throwable $e) {
                throw match (true) {
                    $iterating => CoercionException::withValue(null, $this->toString(), PathExpression::iteratorError($k), $e),
                    $trying_key => CoercionException::withValue($k, $this->toString(), PathExpression::iteratorKey($k), $e),
                    !$trying_key => CoercionException::withValue($v, $this->toString(), PathExpression::path($k), $e)
                };
            }

            /** @var iterable<Tk, Tv> */
            return Iter\Iterator::from((static function () use ($entries) {
                foreach ($entries as [$key, $value]) {
                    yield $key => $value;
                }
            }));
        }

        throw CoercionException::withValue($value, $this->toString());
    }

    /**
     * @throws AssertException
     *
     * @return iterable<Tk, Tv>
     *
     * @psalm-assert iterable<Tk, Tv> $value
     */
    public function assert(mixed $value): iterable
    {
        if (is_iterable($value)) {
            /** @var Type\Type<Tk> $key_type */
            $key_type = $this->key_type;
            /** @var Type\Type<Tv> $value_type */
            $value_type = $this->value_type;

            /** @var list<array{Tk, Tv}> $entries */
            $entries = [];

            $k = $v = null;
            $trying_key = true;

            try {
                /**
                 * @var Tk $k
                 * @var Tv $v
                 */
                foreach ($value as $k => $v) {
                    $trying_key = true;
                    $k_result = $key_type->assert($k);
                    $trying_key = false;
                    $v_result = $value_type->assert($v);

                    $entries[] = [$k_result, $v_result];
                }
            } catch (AssertException $e) {
                throw match ($trying_key) {
                    true => AssertException::withValue($k, $this->toString(), PathExpression::iteratorKey($k), $e),
                    false => AssertException::withValue($v, $this->toString(), PathExpression::path($k), $e)
                };
            }

            /** @var iterable<Tk, Tv> */
            return Iter\Iterator::from((static function () use ($entries) {
                foreach ($entries as [$key, $value]) {
                    yield $key => $value;
                }
            }));
        }

        throw AssertException::withValue($value, $this->toString());
    }

    public function toString(): string
    {
        return Str\format('iterable<%s, %s>', $this->key_type->toString(), $this->value_type->toString());
    }
}
