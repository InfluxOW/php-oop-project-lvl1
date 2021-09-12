<?php

declare(strict_types=1);

namespace Influx\Validator;

use Closure;
use Error;
use Influx\Validator\Validators\ArrayValidator;
use Influx\Validator\Validators\NumberValidator;
use Influx\Validator\Validators\StringValidator;
use Influx\Validator\Validators\Validator as ValidatorInterface;

/**
 * @method StringValidator string()
 * @method NumberValidator number()
 * @method ArrayValidator array()
 */
class Validator
{
    /** @var Array<class-string<ValidatorInterface>> */
    private $validators;

    public function __construct()
    {
        $this->validators = [
            StringValidator::getName() => StringValidator::class,
            NumberValidator::getName() => NumberValidator::class,
            ArrayValidator::getName() => ArrayValidator::class,
        ];
    }

    public function __call(string $name, array $arguments): ValidatorInterface
    {
        if (array_key_exists($name, $this->validators)) {
            return new $this->validators[$name]();
        }

        throw new Error("Call to undefined method " . $this::class . "::" . $name . "()");
    }

    public function addValidator(string $name, string $method, Closure $validate): void
    {
        if (array_key_exists($name, $this->validators)) {
            /** @var ValidatorInterface $validator */
            $validator = $this->validators[$name];
            $validator::setCustomValidationRule($validate, $method);
        }
    }
}
