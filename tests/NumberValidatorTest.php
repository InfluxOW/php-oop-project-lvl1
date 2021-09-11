<?php

declare(strict_types=1);

namespace Influx\Validator\Tests;

use Influx\Validator\Validators\NumberValidator;
use PHPUnit\Framework\TestCase;

class NumberValidatorTest extends TestCase
{
    private NumberValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new NumberValidator();
    }

    /** @test */
    public function it_can_verify_if_value_is_int(): void
    {
        $this->assertTrue($this->validator->isValid(123456789));
        $this->assertTrue($this->validator->isValid(null));

        $this->assertFalse($this->validator->isValid('Test string'));
        $this->assertFalse($this->validator->isValid([]));
        $this->assertFalse($this->validator->required()->isValid(null));
    }

    /** @test */
    public function it_can_verify_if_value_is_positive(): void
    {
        $positiveNumberValidator = $this->validator->positive();

        $this->assertTrue($positiveNumberValidator->isValid(10));
        $this->assertFalse($positiveNumberValidator->isValid(-10));
    }

    /** @test */
    public function it_can_verify_if_value_is_in_range(): void
    {
        $rangeNumberValidator = $this->validator->range(-10, 10);

        $this->assertTrue($rangeNumberValidator->isValid(0));

        $this->assertFalse($rangeNumberValidator->isValid(100));
    }

    /** @test */
    public function it_can_verify_if_value_satisfied_complex_conditions(): void
    {
        $complexValidator = $this->validator->positive()->range(-10, 10);

        $this->assertTrue($complexValidator->isValid(10));
        $this->assertFalse($complexValidator->isValid(-5));
    }
}
