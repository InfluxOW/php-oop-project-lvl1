<?php

declare(strict_types=1);

namespace Hexlet\Validator\Tests;

use Hexlet\Validator\Validator;
use Hexlet\Validator\Validators\Validator as ValidatorInterface;
use PHPUnit\Framework\TestCase;

class CustomValidatorTest extends TestCase
{
    /** @test */
    public function it_can_be_extended_with_custom_validators(): void
    {
        $validator = new Validator();

        /*
         * StartsWith custom validator
         * */

        $validator->addValidator('test', 'startsWith', static fn ($value, $start) => str_starts_with((string) $value, (string) $start));

        /**
         * @phpstan-ignore-next-line
         * @var ValidatorInterface $startsWithCustomValidator
         */
        $startsWithCustomValidator = $validator->test()->test('startsWith', 'H');

        $this->assertTrue($startsWithCustomValidator->isValid('Hexlet'));
        $this->assertFalse($startsWithCustomValidator->isValid('exlet'));

        /*
         * Min custom validator
         * */

        $validator->addValidator('test', 'min', static fn ($value, $min) => $value >= $min);

        /**
         * @phpstan-ignore-next-line
         * @var ValidatorInterface $minCustomValidator
         */
        $minCustomValidator = $validator->test()->test('min', 5);

        $this->assertTrue($minCustomValidator->isValid(6));
        $this->assertFalse($minCustomValidator->isValid(4));

        /*
         * Complex custom validator
         * */

        /**
         * @phpstan-ignore-next-line
         * @var ValidatorInterface $complexCustomValidator
         */
        $complexCustomValidator = $validator->test()->test('startsWith', 555)->test('min', 600);

        $this->assertTrue($complexCustomValidator->isValid(5551));
        $this->assertFalse($complexCustomValidator->isValid(555));
    }
}
