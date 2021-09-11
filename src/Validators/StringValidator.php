<?php

declare(strict_types=1);

namespace Influx\Validator\Validators;

class StringValidator extends Validator
{
    public function __construct()
    {
        $this->validators = collect([
            static fn (mixed $value) => is_string($value)
        ]);
    }

    public static function getName(): string
    {
        return 'string';
    }

    public function required(): self
    {
        $this->allowsNull = false;
        $this->validators->add(static fn (mixed $value) => $value !== '');

        return $this;
    }

    public function contains(string $string): self
    {
        $this->validators->add(static fn (mixed $value) => str_contains($value, $string));

        return $this;
    }

    public function minLength(int $minLength): self
    {
        $this->validators->add(static fn (mixed $value) => strlen($value) > $minLength);

        return $this;
    }
}
