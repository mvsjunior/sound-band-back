<?php

namespace Tests\Feature\Musical;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ChordRoutesTest extends TestCase
{
    use RefreshDatabase;

    private static int $categorySequence = 1;

    private function seedCategory(?string $name = null): int
    {
        $name = $name ?? ('Category ' . self::$categorySequence++);

        return DB::table('music_categories')->insertGetId([
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

    private function seedChord(array $overrides = []): int
    {
        $now = now();
        $content = $overrides['content'] ?? 'Chord content';
        unset($overrides['content']);

        $contentId = DB::table('chord_contents')->insertGetId([
            'content' => $content,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $data = array_merge([
            'music_id' => $this->seedMusic(),
            'tone' => $overrides['tone'] ?? 'major',
            'chord_content_id' => $contentId,
            'version' => $overrides['version'] ?? 'Guitar version in Gm',
        ], $overrides);

        return DB::table('chords')->insertGetId($data);
    }

    public function test_music_list_includes_chords_summary(): void
    {
        $musicId = $this->seedMusic([
            'name' => 'Chorded song',
        ]);

        $contentId = DB::table('chord_contents')->insertGetId([
            'content' => 'Chord content',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $chordId = DB::table('chords')->insertGetId([
            'music_id' => $musicId,
            'tone' => 'minor',
            'chord_content_id' => $contentId,
            'version' => 'Guitar version in Gm',
        ]);

        $response = $this->getJson('/api/musical/music?id=' . $musicId);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertSame($musicId, $response->json('data.items.0.id'));
        $this->assertCount(1, $response->json('data.items.0.chords'));
        $this->assertSame($chordId, $response->json('data.items.0.chords.0.id'));
    }

    public function test_can_list_chords(): void
    {
        $musicId = $this->seedMusic();
        $contentId = DB::table('chord_contents')->insertGetId([
            'content' => 'Chord content',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $chordId = DB::table('chords')->insertGetId([
            'music_id' => $musicId,
            'tone' => 'major',
            'chord_content_id' => $contentId,
            'version' => 'Guitar version in Gm',
        ]);

        $response = $this->getJson('/api/musical/chords?musicId=' . $musicId);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertCount(1, $response->json('data.items'));
        $this->assertSame($chordId, $response->json('data.items.0.id'));
    }

    public function test_can_show_chord_details(): void
    {
        $chordId = $this->seedChord([
            'content' => 'Detailed chord content',
        ]);

        $response = $this->getJson('/api/musical/chords/show?id=' . $chordId);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertSame('Detailed chord content', $response->json('data.content'));
    }

    public function test_can_store_chord(): void
    {
        $musicId = $this->seedMusic();

        $payload = [
            'musicId' => $musicId,
            'tone' => 'major',
            'version' => 'Guitar version in Gm',
            'content' => 'Chord content',
        ];

        $response = $this->postJson('/api/musical/chords/store', $payload);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => ['id', 'musicId', 'tone', 'version', 'content'],
            ]);

        $this->assertDatabaseHas('chords', [
            'music_id' => $musicId,
            'tone' => 'major',
            'version' => 'Guitar version in Gm',
        ]);

        $this->assertDatabaseHas('chord_contents', [
            'content' => 'Chord content',
        ]);
    }

    public function test_can_update_chord(): void
    {
        $musicId = $this->seedMusic();
        $chordId = $this->seedChord([
            'music_id' => $musicId,
            'tone' => 'minor',
            'content' => 'Old content',
            'version' => 'Old version',
        ]);

        $payload = [
            'id' => $chordId,
            'musicId' => $musicId,
            'tone' => 'major',
            'version' => 'Updated version',
            'content' => 'Updated content',
        ];

        $response = $this->putJson('/api/musical/chords/update', $payload);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('chords', [
            'id' => $chordId,
            'version' => 'Updated version',
            'tone' => 'major',
        ]);

        $contentId = DB::table('chords')->where('id', '=', $chordId)->value('chord_content_id');

        $this->assertDatabaseHas('chord_contents', [
            'id' => $contentId,
            'content' => 'Updated content',
        ]);
    }

    public function test_can_delete_chord(): void
    {
        $chordId = $this->seedChord();
        $contentId = DB::table('chords')->where('id', '=', $chordId)->value('chord_content_id');

        $response = $this->deleteJson('/api/musical/chords/delete', [
            'id' => $chordId,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('chords', [
            'id' => $chordId,
        ]);

        $this->assertDatabaseMissing('chord_contents', [
            'id' => $contentId,
        ]);
    }
}
