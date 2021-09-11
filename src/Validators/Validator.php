<?php

declare(strict_types=1);

namespace Influx\Validator\Validators;

use Tightenco\Collect\Support\Collection;
use Closure;

abstract class Validator
{
    protected bool $allowsNull = true;
    protected Collection $validators;

    abstract public static function getName(): string;

    public function isValid(mixed $value): bool
    {
        if ($value === null && $this->allowsNull) {
            return true;
        }

        return $this->validators->every(fn (Closure $validate) => $validate($value));
    }
}
