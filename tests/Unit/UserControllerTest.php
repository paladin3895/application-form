<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Token;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

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
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreatingUserSuccess()
    {
        $validToken = Token::create([
            'email' => 'duydatyds+1@gmail.com',
        ])->token;
        $response = $this->json('POST', '/user', [
            'firstname' => 'Dat',
            'lastname' => 'Pham',
            'email' => 'duydatyds+1@gmail.com',
            'phone' => '0123456789',
            'dob' => '1990-01-01',
            'address' => '301/421 Queen street',
            'token' => $validToken,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 1,
            ]);
    }

    public function testCreatingUserFailedDueToDuplicateEmail()
    {
        $validToken = Token::create([
            'email' => 'duydatyds@gmail.com',
        ])->token;
        $response = $this->json('POST', '/user', [
            'firstname' => 'Dat',
            'lastname' => 'Pham',
            'email' => 'duydatyds@gmail.com',
            'phone' => '0123456789',
            'dob' => '1990-01-01',
            'address' => '301/421 Queen street',
            'token' => $validToken,
        ]);

        $response
            ->assertStatus(409)
            ->assertJson([
                'status' => 0,
            ]);
    }

    public function testCreatingUserFailedDueToInvalidToken()
    {
        $response = $this->json('POST', '/user', [
            'firstname' => 'Dat',
            'lastname' => 'Pham',
            'email' => 'duydatyds@gmail.com',
            'phone' => '0123456789',
            'dob' => '1990-01-01',
            'address' => '301/421 Queen street',
            'token' => 'invalid',
        ]);

        $response
            ->assertStatus(409)
            ->assertJson([
                'status' => 0,
            ]);
    }

    public function testCheckingUserEmailExists()
    {
        $response = $this->json('GET', '/user/duydatyds@gmail.com');

        $response
            ->assertStatus(200)
            ->assertJson([
                'exists' => 1,
            ]);
    }

    public function testCheckingUserEmailNotExists()
    {
        $response = $this->json('GET', '/user/duydatyds+2@gmail.com');

        $response
            ->assertStatus(200)
            ->assertJson([
                'exists' => 0,
            ]);
    }
}
