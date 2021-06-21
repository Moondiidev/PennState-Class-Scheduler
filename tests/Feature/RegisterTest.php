<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function guest_can_register_for_the_site()
    {

        $data = ["name" => "Jennifer Student", "email" => "jnr332@psu.edu", "password" => "someRandomPassword6",
        "password_confirmation" => "someRandomPassword6"];

        $response = $this->post('register', $data);

        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', ["name" => "Jennifer Student", "email" => "jnr332@psu.edu"]);
    }

    /** @test */
    public function guest_can_not_register_with_out_a_penn_state_email_address()
    {

        $data = ["name" => "Jennifer Student", "email" => "jnr332@gmail.com", "password" => "someRandomPassword6",
                 "password_confirmation" => "someRandomPassword6"];

        $response = $this->from(route('register'))->post('register', $data);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrorsIn('email');

        $this->assertDatabaseMissing('users', ["name" => "Jennifer Student", "email" => "jnr332@psu.edu"]);
    }
}
