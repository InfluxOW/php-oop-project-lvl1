<?php

declare(strict_types=1);

namespace Hexlet\Validator\Validators;

use Closure;
use Error;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class Validator
{
    private Collection $appliedValidationRules;
    public static ?Collection $customValidationRules = null;
    protected static string $name;

    public function __construct()
    {
        $this->appliedValidationRules = collect([]);
    }

    public static function getName(): string
    {
        return static::$name;
    }

    public static function setName(string $name): void
    {
        self::$name = $name;
    }

    public function isValid(mixed $value): bool
    {
        try {
            $isValid = $this->appliedValidationRules->every(fn (Closure $validate) => $validate($value));
        } catch (Exception | Error) {
            $isValid = false;
        }

        return $isValid;
    }

    protected function applyValidationRule(Closure $validate, string $key): void
    {
        $this->appliedValidationRules->offsetSet($key, $validate);
    }

    public static function setCustomValidationRule(Closure $validate, string $key): void
    {
        if (isset(self::$customValidationRules)) {
            self::$customValidationRules->offsetSet($key, $validate);
        } else {
            self::$customValidationRules = collect([$key => $validate]);
        }
    }

    /** @param mixed ...$arguments */
    public function test(string $rule, ...$arguments): self
    {
        if (isset(self::$customValidationRules) && self::$customValidationRules->has($rule)) {
            $this->applyValidationRule(static fn (mixed $value) => self::$customValidationRules->get($rule)($value, ...$arguments), Str::slug($rule, '_'));
        }

        return $this;
    }
}
