<?php

use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Post\Item;

test('post with no media displays without swiper initialization', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    
    // Remove the default media created by factory
    $post->media()->delete();
    
    $component = Livewire::test(Item::class, ['post' => $post->fresh()]);
    
    // Verify media count is 0
    expect($post->fresh()->media()->count())->toBe(0);
    
    // Verify basic post info is displayed
    $component->assertSee($post->user->name)
              ->assertSee($post->description);
    
    // Verify mediaCount is set to 0 in Alpine.js
    $html = $component->html();
    expect($html)->toContain('mediaCount: 0');
});

test('post with single media item has loop disabled', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    
    // Ensure only one media item exists
    $post->media()->delete();
    Media::factory()->post()->create([
        'mediable_type' => get_class($post),
        'mediable_id' => $post->id
    ]);
    
    $component = Livewire::test(Item::class, ['post' => $post->fresh()]);
    
    // Verify media count is 1
    expect($post->fresh()->media()->count())->toBe(1);
    
    // Verify mediaCount is set to 1 in Alpine.js
    $html = $component->html();
    expect($html)->toContain('mediaCount: 1');
    
    // Verify loop configuration will be false (mediaCount >= 2 evaluates to false when mediaCount is 1)
    expect($html)->toContain('loop: mediaCount >= 2');
});

test('post with two media items has loop enabled', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    
    // Ensure two media items exist
    $post->media()->delete();
    Media::factory()->post()->count(2)->create([
        'mediable_type' => get_class($post),
        'mediable_id' => $post->id
    ]);
    
    $component = Livewire::test(Item::class, ['post' => $post->fresh()]);
    
    // Verify media count is 2
    expect($post->fresh()->media()->count())->toBe(2);
    
    // Verify mediaCount is set to 2 in Alpine.js
    $html = $component->html();
    expect($html)->toContain('mediaCount: 2');
    
    // Verify navigation controls ARE rendered (because count > 1)
    expect($html)->toContain('swiper-button-next');
    expect($html)->toContain('swiper-button-prev');
});

test('post with multiple media items has loop enabled and navigation visible', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);
    
    // Ensure multiple media items exist
    $post->media()->delete();
    Media::factory()->post()->count(3)->create([
        'mediable_type' => get_class($post),
        'mediable_id' => $post->id
    ]);
    
    $component = Livewire::test(Item::class, ['post' => $post->fresh()]);
    
    // Verify media count is 3
    expect($post->fresh()->media()->count())->toBe(3);
    
    // Verify mediaCount is set to 3 in Alpine.js
    $html = $component->html();
    expect($html)->toContain('mediaCount: 3');
    
    // Verify navigation controls ARE rendered (because count > 1)
    expect($html)->toContain('swiper-button-next');
    expect($html)->toContain('swiper-button-prev');
    
    // Verify all media items are rendered
    $post->fresh()->media->each(function ($media) use ($component) {
        $component->assertSee($media->url);
    });
});

test('swiper configuration adapts to media count correctly', function () {
    $user = User::factory()->create();
    
    // Test with 0 media
    $post0 = Post::factory()->create(['user_id' => $user->id]);
    $post0->media()->delete();
    expect($post0->fresh()->media()->count())->toBe(0);
    
    // Test with 1 media
    $post1 = Post::factory()->create(['user_id' => $user->id]);
    $post1->media()->delete();
    Media::factory()->post()->create([
        'mediable_type' => get_class($post1),
        'mediable_id' => $post1->id
    ]);
    expect($post1->fresh()->media()->count())->toBe(1);
    
    // Test with 2 media
    $post2 = Post::factory()->create(['user_id' => $user->id]);
    $post2->media()->delete();
    Media::factory()->post()->count(2)->create([
        'mediable_type' => get_class($post2),
        'mediable_id' => $post2->id
    ]);
    expect($post2->fresh()->media()->count())->toBe(2);
    
    // Test with 5 media
    $post5 = Post::factory()->create(['user_id' => $user->id]);
    $post5->media()->delete();
    Media::factory()->post()->count(5)->create([
        'mediable_type' => get_class($post5),
        'mediable_id' => $post5->id
    ]);
    expect($post5->fresh()->media()->count())->toBe(5);
});
