<?php

namespace Tests\Feature\Owner;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Owner;
use App\Models\Image;

class ImageControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->owner = Owner::factory()->create();
    }

    public function test_image_index()
    {
        $image = Image::factory()->create(["owner_id" => $this->owner->id]);
        $response = $this->actingAs($this->owner, 'owners')->get(route('owner.images.index'));
        $response->assertSuccessful();
        $response->assertSee($image->filename);
    }

    public function test_image_upload()
    {
        $image_file =  UploadedFile::fake()->image('test_file.jpg', 1080, 800)->size(1200);
        $response = $this->actingAs($this->owner, 'owners')->post(route('owner.images.store'), ['files' => [$image_file]]);
        $response->assertRedirect(route('owner.images.index'));
        $uploaded_file = Image::where('owner_id', $this->owner->id)->first();
        $this->actingAs($this->owner, 'owners')->post(route('owner.images.destroy', $uploaded_file->id))->assertRedirect(route('owner.images.index'));
    }
}
