<?php

declare(strict_types=1);

namespace Hexlet\Validator\Validators;

use Hexlet\Validator\Enums\NumberValidatorRuleKey;

class NumberValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->setValidator(static fn (mixed $value) => is_int($value) || is_null($value), NumberValidatorRuleKey::VALUE_TYPE);
    }

    public static function getName(): string
    {
        return 'number';
    }

    public function required(): self
    {
        $this->setValidator(static fn (mixed $value) => is_int($value), NumberValidatorRuleKey::VALUE_TYPE);

        return $this;
    }

    public function positive(): self
    {
        $this->setValidator(static fn (mixed $value) => $value > 0, NumberValidatorRuleKey::NUMBER_TYPE);

        return $this;
    }

    public function range(int $min, int $max): self
    {
        $this->setValidator(static fn (mixed $value) => in_array($value, range($min, $max), true), NumberValidatorRuleKey::RANGE);

        return $this;
    }
}
