<?php

declare(strict_types=1);

namespace Hexlet\Validator;

use Closure;
use Error;
use Hexlet\Validator\Validators\ArrayValidator;
use Hexlet\Validator\Validators\NumberValidator;
use Hexlet\Validator\Validators\StringValidator;
use Hexlet\Validator\Validators\Validator as AbstractValidator;
use Illuminate\Support\Collection;

/**
 * @method StringValidator string()
 * @method NumberValidator number()
 * @method ArrayValidator array()
 */
final class Validator
{
    /** @var Collection<class-string<AbstractValidator>> */
    private Collection $validators;
    /** @var Collection<class-string<AbstractValidator>> */
    private Collection $customValidators;

    public function __construct()
    {
        $this->validators = collect([
            StringValidator::getName() => StringValidator::class,
            NumberValidator::getName() => NumberValidator::class,
            ArrayValidator::getName() => ArrayValidator::class,
        ]);
        $this->customValidators = collect([]);
    }

    public function __call(string $method, array $arguments): AbstractValidator
    {
        $validatorClass = $this->getValidatorClassByName($method);

        if ($validatorClass === null) {
            throw new Error("Call to undefined method " . $this::class . "::" . $method . "()");
        }

        return new $validatorClass();
    }

    public function addValidator(string $name, string $ruleKey, Closure $validate): self
    {
        $validatorClass = $this->getValidatorClassByName($name);

        if ($validatorClass === null) {
            $validatorClass = new class extends AbstractValidator {
            };
            $validatorClass::setName($name);
            $validatorClassAlias = ucwords($name) . 'Validator';
            class_alias($validatorClass::class, $validatorClassAlias);
            $this->customValidators->offsetSet($validatorClass::getName(), $validatorClassAlias);
        }

        $validatorClass::setCustomValidationRule($validate, $ruleKey);

        return $this;
    }

    private function getValidatorClassByName(string $name): ?string
    {
        $validatorClass = $this->validators->merge($this->customValidators)->get($name);

        return (is_string($validatorClass) && class_exists($validatorClass)) ? $validatorClass : null;
    }
}
