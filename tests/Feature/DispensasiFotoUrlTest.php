<?php

namespace Tests\Feature;

use App\Models\Dispensasi;
use Tests\TestCase;

class DispensasiFotoUrlTest extends TestCase
{
    public function test_it_builds_public_url_for_foto_selfie(): void
    {
        $dispensasi = new Dispensasi([
            'foto_selfie' => 'selfies/test-photo.jpg',
        ]);

        $this->assertIsString($dispensasi->foto_selfie_url);
        $this->assertStringContainsString('/storage/selfies/test-photo.jpg', $dispensasi->foto_selfie_url);
    }
}
