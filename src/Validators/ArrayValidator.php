<?php

declare(strict_types=1);

namespace Hexlet\Validator\Validators;

use Hexlet\Validator\Enums\ArrayValidatorRuleKey;

class ArrayValidator extends Validator
{
    protected static string $name = 'array';

    public function __construct()
    {
        parent::__construct();

        $this->applyValidationRule(static fn (mixed $value) => is_array($value) || is_null($value), ArrayValidatorRuleKey::VALUE_TYPE);
    }

    public function required(): self
    {
        $this->applyValidationRule(static fn (mixed $value) => is_array($value), ArrayValidatorRuleKey::VALUE_TYPE);

        return $this;
    }

    public function sizeof(int $size): self
    {
        $this->applyValidationRule(static fn (mixed $value) => count($value) === $size, ArrayValidatorRuleKey::SIZE_OF);

        return $this;
    }

    public function shape(array $shape): self
    {
        $validateShape = static function (array $array) use ($shape): bool {
            return collect($shape)->every(static function (Validator $validator, string $key) use ($array) {
                return $validator->isValid($array[$key]);
            });
        };

        $this->applyValidationRule($validateShape, ArrayValidatorRuleKey::SHAPE);

        return $this;
    }
}
