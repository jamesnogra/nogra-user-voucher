<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the root page
     */
    public function test_example(): void
    {
        // Check the homepage
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('API Project Only');
    }

    /**
     * Test create user with valid data.
     *
     * @return void
     */
    public function testStoreMethodWithValidData()
    {
        Mail::fake(); // Prevent actual email sending, fakes the Mail facade

        $userData = [
            'username' => 'petergriffin',
            'first_name' => 'Peter',
            'email' => 'peter@yahoo.com',
            'password' => 'somelongpassword',
        ];
        $response = $this->postJson('/api/user/create', $userData);

        // Check if response is the same
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'message' => 'User created successfully',
            ]);

        // Check DB contents
        $this->assertDatabaseHas('users', [
            'username' => 'petergriffin',
            'email' => 'peter@yahoo.com',
        ]);
    }

    /**
     * Test create user with existing username
     *
     * @return void
     */
    public function testStoreMethodWithExistingUsernameData()
    {
        Mail::fake();
        $sameUsername = 'petergriffin';

        $userData = [
            'username' => $sameUsername,
            'first_name' => 'Peter',
            'email' => 'peter@yahoo.com',
            'password' => 'somelongpassword',
        ];
        $response = $this->postJson('/api/user/create', $userData);

        // Create a new data with the same username
        $userDataNew = [
            'username' => $sameUsername,
            'first_name' => 'Peter1',
            'email' => 'peter1@yahoo.com',
            'password' => 'somelongpassword111',
        ];
        $response = $this->postJson('/api/user/create', $userDataNew);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test create user with existing email
     *
     * @return void
     */
    public function testStoreMethodWithExistingEmailData()
    {
        Mail::fake();
        $sameEmail = 'peter@yahoo.com';

        $userData = [
            'username' => 'petergriffin',
            'first_name' => 'Peter',
            'email' => $sameEmail,
            'password' => 'somelongpassword',
        ];
        $response = $this->postJson('/api/user/create', $userData);

        // Create a new data with the same email
        $userDataNew = [
            'username' => 'petergriffin1',
            'first_name' => 'Peter1',
            'email' => $sameEmail,
            'password' => 'somelongpassword111',
        ];
        $response = $this->postJson('/api/user/create', $userDataNew);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test create user with no data.
     *
     * @return void
     */
    public function testStoreMethodWithNoData()
    {
        Mail::fake();

        $userData = [];
        $response = $this->postJson('/api/user/create', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test login method with valid credentials.
     *
     * @return void
     */
    public function testLoginWithValidCredentials()
    {
        Mail::fake();
        $username = 'petergriffin';
        $password = '123456';

        // Create a user for testing
        $userData = [
            'username' => $username,
            'first_name' => 'Peter',
            'email' => 'peter.griffin@gmail.com',
            'password' => $password,
        ];
        $response = $this->postJson('/api/user/create', $userData);
        $userId = $response['user']['id'];

        // Send a POST request with valid credentials
        $response = $this->postJson('/api/user/login', [
            'username' => $username,
            'password' => $password,
        ]);

        // Assert that the response status is 200 OK
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'token',
                'message',
                'expiry',
            ]);

        // Assert that the token is stored for the user
        $this->assertDatabaseHas('user_tokens', [
            'user_id' => $userId,
        ]);
    }

    /**
     * Test login method with invalid credentials.
     *
     * @return void
     */
    public function testLoginWithInvalidCredentials()
    {
        Mail::fake();

        // Create a user for testing
        $userData = [
            'username' => 'petergriffin',
            'first_name' => 'Peter',
            'email' => 'peter.griffin@gmail.com',
            'password' => '123456',
        ];
        $response = $this->postJson('/api/user/create', $userData);
        $userId = $response['user']['id'];

        // Send a POST request with valid credentials
        $response = $this->postJson('/api/user/login', [
            'username' => 'wrongusername',
            'password' => 'wrongpassword',
        ]);

        // Assert that the response status is 200 OK
        $response->assertStatus(Response::HTTP_UNAUTHORIZED )
            ->assertJson([
                'error' => 'Unauthorized, incorrect username and/or password',
            ]);
    }
}
