<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Token;

class TokenModelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTokenGenerating()
    {
        $token = new Token([
            'email' => 'test@gmail.com',
        ]);
        $this->assertArrayHasKey('email', $token->toArray());
        $this->assertArrayHasKey('token', $token->toArray());
        $this->assertArrayHasKey('expired_at', $token->toArray());
    }
}
