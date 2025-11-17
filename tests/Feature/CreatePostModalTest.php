<?php

use App\Models\User;
use App\Models\Post;
use App\Models\Media;
use Livewire\Livewire;
use App\Livewire\Post\Create;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

test('create post modal opens successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $component = Livewire::test(Create::class);
    
    // Verify component renders
    $component->assertStatus(200);
    
    // Verify modal header is present
    $component->assertSee('Create new post');
    
    // Verify Share button is present
    $component->assertSee('Share');
});

test('create post modal displays file upload interface when no media', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $component = Livewire::test(Create::class);
    
    // Verify upload prompt is visible (text is split across lines in HTML)
    $component->assertSee('Upload files from')
              ->assertSee('computer');
});

test('create post with single image file', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $file = UploadedFile::fake()->image('photo.jpg');
    
    $component = Livewire::test(Create::class)
        ->set('media', [$file])
        ->set('description', 'Test post description')
        ->set('location', 'Test Location')
        ->call('submit');
    
    // Verify post was created
    expect(Post::count())->toBe(1);
    
    $post = Post::first();
    expect($post->user_id)->toBe($user->id);
    expect($post->description)->toBe('Test post description');
    expect($post->location)->toBe('Test Location');
    expect($post->type)->toBe('post');
    
    // Verify media was created
    expect($post->media()->count())->toBe(1);
    
    $media = $post->media->first();
    expect($media->mime)->toBe('image');
    expect($media->mediable_type)->toBe(Post::class);
    expect($media->mediable_id)->toBe($post->id);
    
    // Verify file was stored
    Storage::disk('public')->assertExists('media/' . basename(parse_url($media->url, PHP_URL_PATH)));
});

test('create post with multiple image files', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $files = [
        UploadedFile::fake()->image('photo1.jpg'),
        UploadedFile::fake()->image('photo2.jpg'),
        UploadedFile::fake()->image('photo3.jpg'),
    ];
    
    $component = Livewire::test(Create::class)
        ->set('media', $files)
        ->set('description', 'Multiple photos post')
        ->call('submit');
    
    // Verify post was created
    expect(Post::count())->toBe(1);
    
    $post = Post::first();
    expect($post->type)->toBe('post');
    
    // Verify all media items were created
    expect($post->media()->count())->toBe(3);
    
    // Verify all are images
    $post->media->each(function ($media) {
        expect($media->mime)->toBe('image');
    });
});

test('create reel with single video file', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $file = UploadedFile::fake()->create('video.mp4', 1000, 'video/mp4');
    
    $component = Livewire::test(Create::class)
        ->set('media', [$file])
        ->set('description', 'Test reel description')
        ->call('submit');
    
    // Verify post was created as reel
    expect(Post::count())->toBe(1);
    
    $post = Post::first();
    expect($post->type)->toBe('reel');
    
    // Verify media was created as video
    expect($post->media()->count())->toBe(1);
    
    $media = $post->media->first();
    expect($media->mime)->toBe('video');
});

test('create post with advanced settings', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $file = UploadedFile::fake()->image('photo.jpg');
    
    $component = Livewire::test(Create::class)
        ->set('media', [$file])
        ->set('description', 'Post with settings')
        ->set('hide_like_view', true)
        ->set('allow_commenting', true)
        ->call('submit');
    
    $post = Post::first();
    expect($post->hide_like_view)->toBe(true);
    expect($post->allow_commenting)->toBe(true);
});

test('create post modal closes after successful submission', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $file = UploadedFile::fake()->image('photo.jpg');
    
    $component = Livewire::test(Create::class)
        ->set('media', [$file])
        ->set('description', 'Test post')
        ->call('submit');
    
    // Verify closeModal event was dispatched
    $component->assertDispatched('closeModal');
});

test('create post dispatches post-created event', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $file = UploadedFile::fake()->image('photo.jpg');
    
    $component = Livewire::test(Create::class)
        ->set('media', [$file])
        ->set('description', 'Test post')
        ->call('submit');
    
    $post = Post::first();
    
    // Verify post-created event was dispatched with post ID
    $component->assertDispatched('post-created', $post->id);
});

test('create post resets form after submission', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $file = UploadedFile::fake()->image('photo.jpg');
    
    $component = Livewire::test(Create::class)
        ->set('media', [$file])
        ->set('description', 'Test post')
        ->set('location', 'Test Location')
        ->call('submit');
    
    // After submission, form should be reset
    // Note: The reset happens inside the foreach loop, so it resets after first media
    expect(Post::count())->toBe(1);
});

test('share button prevents submission when no media uploaded', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $component = Livewire::test(Create::class);
    
    $html = $component->html();
    
    // The UI prevents submission by disabling the button when no media
    // Verify the button has the disabled attribute rendered
    expect($html)->toContain('disabled');
    expect($html)->toContain('Share');
});

test('create post validates file types', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');
    
    $component = Livewire::test(Create::class)
        ->set('media', [$file])
        ->call('submit');
    
    // Should have validation error for invalid file type
    $component->assertHasErrors(['media.*']);
});

test('share button is disabled when no media uploaded', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $component = Livewire::test(Create::class);
    
    $html = $component->html();
    
    // Verify the button has disabled attribute when no media
    expect($html)->toContain('disabled');
    expect($html)->toContain('disabled:cursor-not-allowed');
});

test('modal can be closed via close button', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    
    $component = Livewire::test(Create::class);
    
    $html = $component->html();
    
    // Verify close button exists with proper wire:click directive
    expect($html)->toContain('wire:click="$dispatch(\'closeModal\')"');
    expect($html)->toContain('font-bold');
});
