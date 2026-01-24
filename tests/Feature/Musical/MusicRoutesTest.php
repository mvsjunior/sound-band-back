<?php

namespace Tests\Feature\Musical;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MusicRoutesTest extends TestCase
{
    use RefreshDatabase;

    private static int $categorySequence = 1;
    private static int $tagSequence = 1;

    private function seedCategory(?string $name = null): int
    {
        $name = $name ?? ('Category ' . self::$categorySequence++);

        return DB::table('music_categories')->insertGetId([
            'name' => $name,
        ]);
    }

    private function seedTag(?string $name = null): int
    {
        $name = $name ?? ('Tag ' . self::$tagSequence++);

        return DB::table('tags')->insertGetId([
            'name' => $name,
        ]);
    }

    private function seedLyrics(string $content = 'Sample lyrics'): int
    {
        $now = now();

        return DB::table('lyrics')->insertGetId([
            'content' => $content,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function seedMusic(array $overrides = []): int
    {
        $now = now();

        $data = array_merge([
            'name' => 'Sample song',
            'artist' => 'Sample artist',
            'lyrics_id' => $this->seedLyrics(),
            'category_id' => $this->seedCategory(),
            'created_at' => $now,
            'updated_at' => $now,
        ], $overrides);

        return DB::table('musics')->insertGetId($data);
    }

    private function attachTags(int $musicId, array $tagIds): void
    {
        $now = now();

        foreach ($tagIds as $tagId) {
            DB::table('musics_tags')->insert([
                'music_id' => $musicId,
                'tag_id' => $tagId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function test_can_list_music_with_filters(): void
    {
        $categoryId = $this->seedCategory('Pop');
        $firstId = $this->seedMusic([
            'name' => 'Hello World',
            'artist' => 'Alpha',
            'category_id' => $categoryId,
        ]);
        $this->seedMusic([
            'name' => 'Goodbye World',
            'artist' => 'Beta',
            'category_id' => $categoryId,
        ]);

        $response = $this->getJson('/api/musical/music?name=Hello');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertCount(1, $response->json('data.items'));
        $this->assertSame($firstId, $response->json('data.items.0.id'));
    }

    public function test_can_store_music_with_tags(): void
    {
        $categoryId = $this->seedCategory('Rock');
        $tagIdOne = $this->seedTag('Acoustic');
        $tagIdTwo = $this->seedTag('Live');

        $payload = [
            'name' => 'New Song',
            'artist' => 'New Artist',
            'lyrics' => 'New lyrics',
            'categoryId' => $categoryId,
            'tagIds' => [$tagIdOne, $tagIdTwo],
        ];

        $response = $this->postJson('/api/musical/music/store', $payload);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => ['id', 'name', 'artist', 'category', 'lyrics', 'tags'],
            ]);

        $this->assertDatabaseHas('musics', [
            'name' => 'New Song',
            'artist' => 'New Artist',
            'category_id' => $categoryId,
        ]);

        $this->assertDatabaseHas('lyrics', [
            'content' => 'New lyrics',
        ]);

        $musicId = $response->json('data.id');
        $this->assertDatabaseHas('musics_tags', [
            'music_id' => $musicId,
            'tag_id' => $tagIdOne,
        ]);
        $this->assertDatabaseHas('musics_tags', [
            'music_id' => $musicId,
            'tag_id' => $tagIdTwo,
        ]);
    }

    public function test_can_update_music_and_sync_tags(): void
    {
        $categoryId = $this->seedCategory('Pop');
        $newCategoryId = $this->seedCategory('Metal');
        $oldTagId = $this->seedTag('Old');
        $newTagId = $this->seedTag('New');

        $musicId = $this->seedMusic([
            'name' => 'Original Song',
            'artist' => 'Original Artist',
            'lyrics_id' => $this->seedLyrics('Old lyrics'),
            'category_id' => $categoryId,
        ]);

        $this->attachTags($musicId, [$oldTagId]);

        $payload = [
            'id' => $musicId,
            'name' => 'Updated Song',
            'artist' => 'Updated Artist',
            'lyrics' => 'Updated lyrics',
            'categoryId' => $newCategoryId,
            'tagIds' => [$newTagId],
        ];

        $response = $this->putJson('/api/musical/music/update', $payload);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('musics', [
            'id' => $musicId,
            'name' => 'Updated Song',
            'artist' => 'Updated Artist',
            'category_id' => $newCategoryId,
        ]);

        $this->assertDatabaseHas('lyrics', [
            'content' => 'Updated lyrics',
        ]);

        $this->assertDatabaseMissing('musics_tags', [
            'music_id' => $musicId,
            'tag_id' => $oldTagId,
        ]);
        $this->assertDatabaseHas('musics_tags', [
            'music_id' => $musicId,
            'tag_id' => $newTagId,
        ]);
    }

    public function test_can_delete_music(): void
    {
        $musicId = $this->seedMusic([
            'name' => 'Delete Song',
        ]);

        $response = $this->deleteJson('/api/musical/music/delete', [
            'id' => $musicId,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('musics', [
            'id' => $musicId,
        ]);
    }
}
