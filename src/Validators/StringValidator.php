<?php

declare(strict_types=1);

namespace Hexlet\Validator\Validators;

use Hexlet\Validator\Enums\StringValidatorRuleKey;

class StringValidator extends Validator
{
    protected static string $name = 'string';

    public function __construct()
    {
        parent::__construct();

        $this->applyValidationRule(static fn (mixed $value) => is_string($value), StringValidatorRuleKey::VALUE_TYPE);
    }

    public function required(): static
    {
        return parent::required()->minLength(1);
    }

    public function minLength(int $minLength): static
    {
        $this->applyValidationRule(static fn (mixed $value) => mb_strlen($value) >= $minLength, StringValidatorRuleKey::MIN_LENGTH);

        return $this;
    }

    public function contains(string $string): static
    {
        $this->applyValidationRule(static fn (mixed $value) => str_contains($value, $string), StringValidatorRuleKey::CONTAINS);

        return $this;
    }
}
