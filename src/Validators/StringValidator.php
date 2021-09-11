<?php

declare(strict_types=1);

namespace Influx\Validator\Validators;

use Influx\Validator\Enums\StringValidatorRuleKey;

class StringValidator extends Validator
{
    public function __construct()
    {
        parent::__construct();

        $this->setValidator(static fn (mixed $value) => is_string($value) || is_null($value), StringValidatorRuleKey::VALUE_TYPE);
    }

    public static function getName(): string
    {
        return 'string';
    }

    public function required(): self
    {
        $this->setValidator(static fn (mixed $value) => is_string($value), StringValidatorRuleKey::VALUE_TYPE);
        $this->minLength(1);

        return $this;
    }

    public function minLength(int $minLength): self
    {
        $this->setValidator(static fn (mixed $value) => strlen($value) > $minLength, StringValidatorRuleKey::MIN_LENGTH);

        return $this;
    }

    public function contains(string $string): self
    {
        $this->setValidator(static fn (mixed $value) => str_contains($value, $string), StringValidatorRuleKey::CONTAINS);

        return $this;
    }
}
