<?php

declare(strict_types=1);

namespace Hexlet\Validator\Tests;

use Hexlet\Validator\Validator;
use Hexlet\Validator\Validators\ArrayValidator;
use PHPUnit\Framework\TestCase;

class ArrayValidatorTest extends TestCase
{
    private ArrayValidator $arrayValidator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->arrayValidator = new ArrayValidator();
    }

    public function testItCanVerifyIfValueIsArray(): void
    {
        $this->assertTrue($this->arrayValidator->isValid([]));
        $this->assertTrue($this->arrayValidator->isValid(null));

        $this->assertFalse($this->arrayValidator->isValid('Test string'));
        $this->assertFalse($this->arrayValidator->isValid(123456789));
        $this->assertFalse($this->arrayValidator->required()->isValid(null));
    }

    public function testItCanVerifyIfValueIsOfSpecifiedSize(): void
    {
        $sizeOfValidator = $this->arrayValidator->sizeof(2);

        $this->assertTrue($sizeOfValidator->isValid([1, 2]));

        $this->assertFalse($sizeOfValidator->isValid([]));
        $this->assertFalse($sizeOfValidator->isValid(null));
    }

    public function testItCanVerifyIfArrayHasSpecifiedShape(): void
    {
        $validator = new Validator();
        $shapeValidator = $this->arrayValidator->shape(['name' => $validator->string()->minLength(3), 'surname' => $validator->string()->minLength(5)->contains('kirk'), 'age' => $validator->number()->positive()]);

        $this->assertTrue($shapeValidator->isValid(['name' => 'Bob', 'surname' => 'Odenkirk', 'age' => 58]));

        $this->assertFalse($shapeValidator->isValid(['name' => 'Bob', 'surname' => null, 'age' => 58]));
        $this->assertFalse($shapeValidator->isValid(['name' => 'B', 'age' => 0]));
    }
}
