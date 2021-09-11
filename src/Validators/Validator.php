<?php

namespace Influx\Validator\Validators;

use Tightenco\Collect\Support\Collection;
use Closure;

abstract class Validator
{
    protected Collection $validators;

    abstract public static function getName(): string;

    public function isValid(mixed $value): bool
    {
        return $this->validators->every(fn (Closure $validate) => $validate($value));
    }
}
