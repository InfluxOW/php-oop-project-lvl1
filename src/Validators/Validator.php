<?php

declare(strict_types=1);

namespace Influx\Validator\Validators;

use Closure;
use Error;
use Exception;
use Tightenco\Collect\Support\Collection;

abstract class Validator
{
    private Collection $validators;
    public static ?Collection $customValidationRules = null;

    public function __construct()
    {
        $this->validators = collect([]);
    }

    abstract public static function getName(): string;

    public function isValid(mixed $value): bool
    {
        try {
            $isValid = $this->validators->every(fn (Closure $validate) => $validate($value));
        } catch (Exception | Error) {
            $isValid = false;
        }

        return $isValid;
    }

    protected function setValidator(Closure $validate, string $key): void
    {
        $this->validators->offsetSet($key, $validate);
    }

    public static function setCustomValidationRule(Closure $validate, string $key): void
    {
        if (self::$customValidationRules === null) {
            self::$customValidationRules = collect([$key => $validate]);
        } else {
            self::$customValidationRules->offsetSet($key, $validate);
        }
    }

    /** @param mixed ...$arguments */
    public function test(string $rule, ...$arguments): self
    {
        if (isset(self::$customValidationRules) && self::$customValidationRules->has($rule)) {
            $this->setValidator(static fn (mixed $value) => self::$customValidationRules->get($rule)($value, ...$arguments), slugify($rule, '_'));
        }

        return $this;
    }
}
