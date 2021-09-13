<?php

declare(strict_types=1);

namespace Hexlet\Validator;

use Closure;
use Error;
use Hexlet\Validator\Validators\ArrayValidator;
use Hexlet\Validator\Validators\NumberValidator;
use Hexlet\Validator\Validators\StringValidator;
use Hexlet\Validator\Validators\Validator as ValidatorInterface;
use Illuminate\Support\Collection;

/**
 * @method StringValidator string()
 * @method NumberValidator number()
 * @method ArrayValidator array()
 */
class Validator
{
    /** @var Collection<class-string<ValidatorInterface>> */
    private Collection $validators;
    /** @var Collection<class-string<ValidatorInterface>> */
    private $customValidators;

    public function __construct()
    {
        $this->validators = collect([
            StringValidator::getName() => StringValidator::class,
            NumberValidator::getName() => NumberValidator::class,
            ArrayValidator::getName() => ArrayValidator::class,
        ]);
        $this->customValidators = collect([]);
    }

    public function __call(string $name, array $arguments): ValidatorInterface
    {
        if ($this->validators->has($name)) {
            $validatorClass = $this->validators->get($name);
            return new $validatorClass();
        }
        if ($this->customValidators->has($name)) {
            $validatorClass = $this->customValidators->get($name);
            return new $validatorClass($name);
        }

        throw new Error("Call to undefined method " . $this::class . "::" . $name . "()");
    }

    public function addValidator(string $name, string $method, Closure $validate): self
    {
        if ($this->validators->has($name)) {
            $validator = $this->validators->get($name);
        } elseif ($this->customValidators->has($name)) {
            $validator = $this->customValidators->get($name);
        } else {
            $validator = new class extends ValidatorInterface
            {
            };
            $validator::setName($name);
            $validatorAlias = ucwords($name) . 'Validator';
            class_alias($validator::class, $validatorAlias);
            $this->customValidators->offsetSet($validator::getName(), $validatorAlias);
        }

        $validator::setCustomValidationRule($validate, $method);

        return $this;
    }
}
