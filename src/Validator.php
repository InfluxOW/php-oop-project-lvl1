<?php

declare(strict_types=1);

namespace Influx\Validator;

use Influx\Validator\Validators\StringValidator;
use Influx\Validator\Validators\Validator as ValidatorInterface;
use Error;

/**
 * @method StringValidator string()
 */
class Validator
{
    /** @var ValidatorInterface[] */
    private $validators;

    public function __construct()
    {
        $this->validators = [
            StringValidator::getName() => new StringValidator(),
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
