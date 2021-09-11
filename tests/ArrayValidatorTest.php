<?php

declare(strict_types=1);

namespace Influx\Validator\Tests;

use Influx\Validator\Validators\ArrayValidator;
use PHPUnit\Framework\TestCase;

class ArrayValidatorTest extends TestCase
{
    private ArrayValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new ArrayValidator();
    }

    /** @test */
    public function it_can_verify_if_value_is_array(): void
    {
        $this->assertTrue($this->validator->isValid([]));
        $this->assertTrue($this->validator->isValid(null));

        $this->assertFalse($this->validator->isValid('Test string'));
        $this->assertFalse($this->validator->isValid(123456789));
        $this->assertFalse($this->validator->required()->isValid(null));
    }

    /** @test */
    public function it_can_verify_if_value_is_of_specified_size(): void
    {
        $sizeOfValidator = $this->validator->sizeof(2);

        $this->assertTrue($sizeOfValidator->isValid([1, 2]));

        $this->assertFalse($sizeOfValidator->isValid([]));
        $this->assertFalse($sizeOfValidator->isValid(null));
    }
}
