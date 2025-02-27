<?php

declare(strict_types=1);

namespace Psl\Tests\Unit\Type;

use Psl\Type;

final class ArrayKeyTypeTest extends TypeTest
{
    public function getType(): Type\TypeInterface
    {
        return Type\array_key();
    }

    public function getValidCoercions(): iterable
    {
        yield [123, 123];
        yield [0, 0];
        yield ['0', '0'];
        yield ['123', '123'];
        yield ['1e23', '1e23'];
        yield [$this->stringable('123'), '123'];
        yield [1.0, 1];
    }

    public function getInvalidCoercions(): iterable
    {
        yield [1.23];
        yield [[]];
        yield [[1]];
        yield [Type\bool()];
        yield [null];
        yield [false];
        yield [true];
        yield [STDIN];
    }

    public function getToStringExamples(): iterable
    {
        yield [$this->getType(), 'array-key'];
    }

    public function testItIsAMemoizedType(): void
    {
        static::assertSame(Type\array_key(), Type\array_key());
    }
}
