<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\ApiController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    /**
     * 不接受的登录方式
     */
    public function testNotAcceptable()
    {
        $response = $this->post('api/login/github');
        $response->assertStatus(406)
                    ->assertJson(['code' => ApiController::CODE_NOT_ACCEPTABLE]);
    }
}
