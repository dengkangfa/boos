<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $mobile = '13590098656';
    protected $password = 'secret';

    public function setUp()
    {
        $user = User::create([
            'mobile' => $this->mobile,
            'password' => $this->password
        ]);
    }

    public function testLogin()
    {
        $this->post('/api/login/password', ['mobile' => $this->mobile, 'password' => $this->password]);
    }

    public function testAvatarUpload()
    {
        Storage::fake('avatars');

        $response = $this->json('POST', '/api/user/avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        // 断言文件已存储...
        Storage::disk('avatars')->assertExists('avatar.jpg');

        // 断言文件不存在...
        Storage::disk('avatars')->assertMissing('missing.jpg');
    }
}
