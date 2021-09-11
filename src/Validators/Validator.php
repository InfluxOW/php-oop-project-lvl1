<?php

declare(strict_types=1);

namespace Influx\Validator\Validators;

use Tightenco\Collect\Support\Collection;
use Closure;
use Exception;
use Error;

abstract class Validator
{
    protected bool $allowsNull = true;
    private Collection $validators;

    public function __construct()
    {
        $this->validators = collect([]);
    }

    abstract public static function getName(): string;

    public function isValid(mixed $value): bool
    {
        try {
            $isValid = $this->validators->every(fn (Closure $validate) => $validate($value));
        }
        catch (Exception|Error)
        {
            $isValid = false;
        }

        return $isValid;
    }

    protected function setValidator(Closure $validate, string $key): void
    {
        $this->validators->offsetSet($key, $validate);
    }
}
