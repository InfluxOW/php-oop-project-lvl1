<?php

declare(strict_types=1);

namespace Influx\Validator\Tests;

use Influx\Validator\Validators\NumberValidator;
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
}
