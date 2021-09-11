<?php

declare(strict_types=1);

namespace Influx\Validator\Tests;

use Influx\Validator\Validators\StringValidator;
use PHPUnit\Framework\TestCase;

class StringValidatorTest extends TestCase
{
    private StringValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new StringValidator();
    }

    /** @test */
    public function it_can_verify_if_value_is_string(): void
    {
        $this->assertTrue($this->validator->isValid('Test string'));

        $this->assertFalse($this->validator->isValid(123456789));
        $this->assertFalse($this->validator->isValid([]));
    }

    /** @test */
    public function it_can_verify_if_value_is_empty_string_or_null(): void
    {
        $this->assertTrue($this->validator->isValid(''));
        $this->assertTrue($this->validator->isValid(null));

        $this->assertFalse($this->validator->required()->isValid(''));
        $this->assertFalse($this->validator->required()->isValid(null));
    }

    /** @test */
    public function it_can_verify_if_value_contains_string(): void
    {
        $this->assertTrue($this->validator->contains('What')->isValid('What does the fox say?'));

        $this->assertFalse($this->validator->contains('what')->isValid('What does the fox say?'));
        $this->assertFalse($this->validator->contains('thefox')->isValid('What does the fox say?'));
    }

    /** @test */
    public function it_can_verify_if_value_is_longer_than_min_length(): void
    {
        $this->assertTrue($this->validator->minLength(10)->isValid('What does the fox say?'));

        $this->assertFalse($this->validator->minLength(0)->isValid(''));
        $this->assertFalse($this->validator->minLength(5)->isValid('Test'));
    }

    /** @test */
    public function it_can_verify_if_value_satisfied_complex_conditions(): void
    {
        $complexValidator = $this->validator->required()->minLength(10)->contains('What');

        $this->assertTrue($complexValidator->isValid('What does the fox say?'));

        $this->assertFalse($complexValidator->isValid('What does'));
        $this->assertFalse($complexValidator->isValid('hat does the fox say?'));
    }
}
