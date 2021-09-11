<?php

declare(strict_types=1);

namespace Influx\Validator\Validators;

class NumberValidator extends Validator
{
    public function __construct()
    {
        $this->validators = collect([
           fn (mixed $value) => is_int($value)
        ]);
    }

    public static function getName(): string
    {
        return 'number';
    }

    public function required(): self
    {
        $this->allowsNull = false;

        return $this;
    }

    public function positive(): self
    {
        $this->validators->add(static fn (mixed $value) => $value > 0);

        return $this;
    }

    public function range(int $min, int $max): self
    {
        $this->validators->add(static fn (mixed $value) => in_array($value, range($min, $max), true));

        return $this;
    }
}
