<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Token;

class TokenControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $validToken;
    protected $invalidToken = 'invalid';
    protected $expiredToken;

    public function setup() {
        parent::setup();
        User::create([
            'firstname' => 'Dat',
            'lastname' => 'Pham',
            'email' => 'duydatyds@gmail.com',
            'phone' => '0123456789',
            'dob' => '1990-01-01',
            'address' => '301/421 Queen street',
        ]);
        $this->validToken = Token::create([
            'email' => 'duydatyds@gmail.com',
        ])->token;

        $expiredToken = new Token(['email' => 'duydatyds@gmail.com']);
        $expiredToken->expired_at = new \DateTime('now -1 hour');
        $expiredToken->save();
        $this->expiredToken = $expiredToken->token;
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testSendingTokenSuccess()
    {
        $response = $this->json('POST', '/token', [
            'email' => 'duydatyds+1@gmail.com',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 1,
            ]);
    }

    public function testSendingTokenFailedDueToDuplicateEmail()
    {
        $response = $this->json('POST', '/token', [
            'email' => 'duydatyds@gmail.com',
        ]);

        $response
            ->assertStatus(409)
            ->assertJson([
                'status' => 0,
            ]);
    }

    public function testCheckingTokenMatching()
    {
        $response = $this->json('GET', "/token/{$this->validToken}/duydatyds@gmail.com");

        $response
            ->assertStatus(200)
            ->assertJson([
                'isValid' => 1,
            ]);
    }

    public function testCheckingTokenNotMatching()
    {
        $response = $this->json('GET', "/token/{$this->invalidToken}/duydatyds@gmail.com");

        $response
            ->assertStatus(200)
            ->assertJson([
                'isValid' => 0,
            ]);
    }


    public function testCheckingTokenExpired()
    {
        $response = $this->json('GET', "/token/{$this->expiredToken}/duydatyds@gmail.com");

        $response
            ->assertStatus(200)
            ->assertJson([
                'isValid' => 0,
            ]);
    }
}
