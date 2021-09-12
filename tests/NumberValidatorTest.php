<?php

declare(strict_types=1);

namespace Hexlet\Validator\Tests;

use Hexlet\Validator\Validator;
use Hexlet\Validator\Validators\NumberValidator;
use PHPUnit\Framework\TestCase;

class NumberValidatorTest extends TestCase
{
    private NumberValidator $numberValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->numberValidator = new NumberValidator();
    }

    /** @test */
    public function it_can_verify_if_value_is_int(): void
    {
        $this->assertTrue($this->numberValidator->isValid(123456789));
        $this->assertTrue($this->numberValidator->isValid(null));

        $this->assertFalse($this->numberValidator->isValid('Test string'));
        $this->assertFalse($this->numberValidator->isValid([]));
        $this->assertFalse($this->numberValidator->required()->isValid(null));
    }

    /** @test */
    public function it_can_verify_if_value_is_positive(): void
    {
        $positiveNumberValidator = $this->numberValidator->positive();

        $this->assertTrue($positiveNumberValidator->isValid(10));
        $this->assertFalse($positiveNumberValidator->isValid(-10));
    }

    /** @test */
    public function it_can_verify_if_value_is_in_range(): void
    {
        $rangeNumberValidator = $this->numberValidator->range(-10, 10);

        $this->assertTrue($rangeNumberValidator->isValid(0));

        $this->assertFalse($rangeNumberValidator->isValid(100));
    }

    /** @test */
    public function it_can_verify_if_value_satisfied_complex_conditions(): void
    {
        $complexValidator = $this->numberValidator->positive()->range(-10, 10);

        $this->assertTrue($complexValidator->isValid(10));
        $this->assertFalse($complexValidator->isValid(-5));
    }

    /** @test */
    public function it_can_be_extended_with_custom_validation_rules(): void
    {
        $validator = new Validator();

        $fn = static fn($value, $min) => $value >= $min;
        $validator->addValidator(NumberValidator::getName(), 'min', $fn);
        $numberValidator = $validator->number()->test('min', 5);

        $this->assertTrue($numberValidator->isValid(6));
        $this->assertFalse($numberValidator->isValid(4));
    }
}
