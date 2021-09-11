<?php

declare(strict_types=1);

namespace Influx\Validator;

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
    /** @var ValidatorInterface[] */
    private $validators;

    public function __construct()
    {
        $this->validators = [
            StringValidator::getName() => new StringValidator(),
            NumberValidator::getName() => new NumberValidator(),
            ArrayValidator::getName() => new ArrayValidator(),
        ];
    }

    public function __call(string $name, array $arguments): ValidatorInterface
    {
        if (array_key_exists($name, $this->validators)) {
            return $this->validators[$name];
        }

        throw new Error("Call to undefined method " . $this::class . "::" . $name . "()");
    }
}
