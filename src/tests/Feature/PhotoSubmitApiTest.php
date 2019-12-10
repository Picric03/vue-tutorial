<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhotoSubmitApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function should_ファイルをアップロードできる(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        $response->assertStatus(201);

        $photo = Photo::first();

        $this->assertRegExp('/^[0-9a-zA-Z-_]{12}$/', $photo->id);
        Storage::disk('public')->assertExists($photo->filename);
    }

    /**
     * @test
     */
    public function should_データベースエラーの場合はファイルを保存しない(): void
    {
        Schema::drop('photos');

        Storage::fake('public');

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'),[
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        $response->assertStatus(500);

        $this->assertCount(0, Storage::disk('public')->files());
    }

    /**
     * @test
     */
    public function should_ファイル保存エラーの場合はDBへの挿入はしない(): void
    {
        Storage::shouldReceive('disk')
            ->once()
            ->andReturnNull();

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        $response->assertStatus(500);
        $this->assertEmpty(Photo::all());
    }
}
