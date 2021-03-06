<?php

declare(strict_types=1);

namespace Hexlet\Validator\Validators;

use Hexlet\Validator\Enums\NumberValidatorRuleKey;

class NumberValidator extends Validator
{
    protected static string $name = 'number';

    public function __construct()
    {
        parent::__construct();

        $this->applyValidationRule(static fn (mixed $value) => is_numeric($value), NumberValidatorRuleKey::VALUE_TYPE);
    }

    public function positive(): static
    {
        $this->applyValidationRule(static fn (mixed $value) => $value > 0, NumberValidatorRuleKey::NUMBER_TYPE);

        return $this;
    }

    public function range(int $min, int $max): static
    {
        $this->applyValidationRule(static fn (mixed $value) => ($value >= $min) && ($value <= $max), NumberValidatorRuleKey::RANGE);

        return $this;
    }
}
