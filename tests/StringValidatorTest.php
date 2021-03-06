<?php

declare(strict_types=1);

namespace Hexlet\Validator\Tests;

use Hexlet\Validator\Validator;
use Hexlet\Validator\Validators\StringValidator;
use PHPUnit\Framework\TestCase;

class StringValidatorTest extends TestCase
{
    private StringValidator $stringValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stringValidator = new StringValidator();
    }

    public function testItCanVerifyIfValueIsString(): void
    {
        $this->assertTrue($this->stringValidator->isValid('Test string'));
        $this->assertTrue($this->stringValidator->isValid(null));

        $this->assertFalse($this->stringValidator->isValid(123456789));
        $this->assertFalse($this->stringValidator->isValid([]));
        $this->assertFalse($this->stringValidator->required()->isValid(null));
    }

    public function testItCanVerifyIfValueIsEmptyStringOrNull(): void
    {
        $this->assertTrue($this->stringValidator->isValid(''));

        $this->assertFalse($this->stringValidator->required()->isValid(''));
    }

    public function testItCanVerifyIfValueContainsString(): void
    {
        $this->assertTrue($this->stringValidator->contains('What')->isValid('What does the fox say?'));

        $this->assertFalse($this->stringValidator->contains('what')->isValid('What does the fox say?'));
        $this->assertFalse($this->stringValidator->contains('thefox')->isValid('What does the fox say?'));
    }

    public function testItCanVerifyIfValueIsLongerThanMinLength(): void
    {
        $this->assertTrue($this->stringValidator->minLength(10)->isValid('What does the fox say?'));
        $this->assertTrue($this->stringValidator->minLength(0)->isValid(''));

        $this->assertFalse($this->stringValidator->minLength(1)->isValid(''));
        $this->assertFalse($this->stringValidator->minLength(5)->isValid('Test'));
    }

    public function testItCanVerifyIfValueSatisfiedComplexConditions(): void
    {
        $complexValidator = $this->stringValidator->required()->minLength(10)->contains('What');

        $this->assertTrue($complexValidator->isValid('What does the fox say?'));

        $this->assertFalse($complexValidator->isValid('What does'));
        $this->assertFalse($complexValidator->isValid('hat does the fox say?'));
    }

    public function testItCanBeExtendedWithCustomValidationRules(): void
    {
        $validator = new Validator();

        $fn = static fn ($value, $start) => str_starts_with($value, $start);
        $validator->addValidator(StringValidator::getName(), 'startsWith', $fn);
        $stringValidator = $validator->string()->test('startsWith', 'H');

        $this->assertTrue($stringValidator->isValid('Hexlet'));
        $this->assertFalse($stringValidator->isValid('exlet'));
    }
}
