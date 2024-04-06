<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;
use Tests\TestCase;

class VoucherControllerTest extends TestCase
{
    private $username = 'harry';
    private $password = 'styles123456';

    /**
     * Test create voucher with valid data.
     *
     * @return void
     */
    public function testStoreMethodWithValidData()
    {
        Mail::fake(); // Prevent actual email sending, fakes the Mail facade

        // Create initial user
        $userData = [
            'username' => $this->username,
            'first_name' => 'Harry',
            'email' => 'harry@yahoo.com',
            'password' => $this->password,
        ];
        $response = $this->postJson('/api/user/create', $userData);

        // Login to get a token
        $response = $this->postJson('/api/user/login', [
            'username' => $this->username,
            'password' => $this->password,
        ]);
        $token = $response['token'];

        // Create one voucher
        $response = $this->postJson('/api/voucher/create', [
            'token' => $token
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        // There should be two tokens, 1 from create user and one from this
        $this->assertEquals($response['totalVouchers'], 2);
    }

    /**
     * Test create voucher until the 10 limit
     *
     * @return void
     */
    public function testStoreMethodUntilTenLimit()
    {
        // Login to get a token
        $response = $this->postJson('/api/user/login', [
            'username' => $this->username,
            'password' => $this->password,
        ]);
        $token = $response['token'];

        // Create vouchers up to 10
        for ($x=3; $x<=10; $x++) {
            $response = $this->postJson('/api/voucher/create', [
                'token' => $token
            ]);
            $response->assertStatus(Response::HTTP_CREATED);
            // There should be two tokens, 1 from create user and one from this
            $this->assertEquals($response['totalVouchers'], $x);
        }

        // Create the 11th voucher, it should fail
        $response = $this->postJson('/api/voucher/create', [
            'token' => $token
        ]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Test get the vouchers
     *
     * @return void
     */
    public function testGetVouchers()
    {
        // Login to get a token
        $response = $this->postJson('/api/user/login', [
            'username' => $this->username,
            'password' => $this->password,
        ]);
        $token = $response['token'];

        $response = $this->getJson("/api/vouchers?token={$token}");
        $allVouchers = $response->decodeResponseJson();
        // There should be 10 after the test testStoreMethodUntilTenLimit
        $this->assertEquals(count($allVouchers), 10);
    }

    /**
     * Test delete but invalid voucher code
     *
     * @return void
     */
    public function testDeleteInvalid()
    {
        // Login to get a token
        $response = $this->postJson('/api/user/login', [
            'username' => $this->username,
            'password' => $this->password,
        ]);
        $token = $response['token'];

        $response = $this->postJson('/api/voucher/delete', [
            'token' => $token,
            'voucher_code' => 'NOT_A_VALID_VOUCHER_CODE'
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * Test delete a valid voucher code
     *
     * @return void
     */
    public function testDeleteValid()
    {
        // Login to get a token
        $response = $this->postJson('/api/user/login', [
            'username' => $this->username,
            'password' => $this->password,
        ]);
        $token = $response['token'];

        $response = $this->getJson("/api/vouchers?token={$token}");
        $allVouchers = $response->decodeResponseJson();

        // Delete one voucher
        $response = $this->postJson('/api/voucher/delete', [
            'token' => $token,
            'voucher_code' => $allVouchers[0]['voucher_code']
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        
        // Check again voucher list if it is just 9 records
        $response = $this->getJson("/api/vouchers?token={$token}");
        $allVouchers = $response->decodeResponseJson();
        $this->assertEquals(count($allVouchers), 9);
    }

    /**
     * Test delete all remaining voucher codes
     *
     * @return void
     */
    public function testDeleteAllVouchers()
    {
        // Login to get a token
        $response = $this->postJson('/api/user/login', [
            'username' => $this->username,
            'password' => $this->password,
        ]);
        $token = $response['token'];

        $response = $this->getJson("/api/vouchers?token={$token}");
        $allVouchers = $response->decodeResponseJson();

        for ($x=0; $x<count($allVouchers); $x++) {
            // Delete one voucher
            $response = $this->postJson('/api/voucher/delete', [
                'token' => $token,
                'voucher_code' => $allVouchers[$x]['voucher_code']
            ]);
            $response->assertStatus(Response::HTTP_CREATED);
        }

        // Check again voucher list if there are no more records
        $response = $this->getJson("/api/vouchers?token={$token}");
        $allVouchers = $response->decodeResponseJson();
        $this->assertEquals(count($allVouchers), 0);
    }

    /**
     * Test create vouchers again from zero to Limit
     *
     * @return void
     */
    public function testStoreMethodFromZeroUntilTenLimit()
    {
        // Login to get a token
        $response = $this->postJson('/api/user/login', [
            'username' => $this->username,
            'password' => $this->password,
        ]);
        $token = $response['token'];

        // Create vouchers up to 10
        for ($x=1; $x<=10; $x++) {
            $response = $this->postJson('/api/voucher/create', [
                'token' => $token
            ]);
            $response->assertStatus(Response::HTTP_CREATED);
            // There should be two tokens, 1 from create user and one from this
            $this->assertEquals($response['totalVouchers'], $x);
        }

        // Create the 11th voucher, it should fail
        $response = $this->postJson('/api/voucher/create', [
            'token' => $token
        ]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
