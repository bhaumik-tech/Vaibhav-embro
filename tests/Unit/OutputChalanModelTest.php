<?php

namespace Tests\Unit;

use App\Models\OutputChalan;
use Tests\TestCase;

class OutputChalanModelTest extends TestCase
{
    public function test_output_chalan_model_exists()
    {
        $this->assertTrue(class_exists(OutputChalan::class));
    }
}
