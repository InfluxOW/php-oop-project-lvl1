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

        $this->applyValidationRule(static fn (mixed $value) => is_string($value) || is_null($value), StringValidatorRuleKey::VALUE_TYPE);
    }

    public function required(): self
    {
        $this->applyValidationRule(static fn (mixed $value) => is_string($value), StringValidatorRuleKey::VALUE_TYPE);
        $this->minLength(1);

        return $this;
    }

    public function minLength(int $minLength): self
    {
        $this->applyValidationRule(static fn (mixed $value) => strlen($value) >= $minLength, StringValidatorRuleKey::MIN_LENGTH);

        return $this;
    }

    public function contains(string $string): self
    {
        $this->applyValidationRule(static fn (mixed $value) => str_contains($value, $string), StringValidatorRuleKey::CONTAINS);

        return $this;
    }
}
