<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * @test
     */
    public function 環境確認(): void
    {
        $this->assertEquals('testing', $this->app->environment());
    }

    /**
     * @test
     */
    public function DB確認(): void
    {
        $this->assertSame('sqlite_testing', \Config::get('database.default'));
    }
}
