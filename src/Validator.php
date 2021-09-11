<?php

declare(strict_types=1);

namespace Influx\Validator;

use Error;
use Influx\Validator\Validators\NumberValidator;
use Influx\Validator\Validators\StringValidator;
use Influx\Validator\Validators\Validator as ValidatorInterface;

/**
 * @method StringValidator string()
 * @method NumberValidator number()
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
