<?php

namespace Tests\Unit;

use App\WrestlerBio;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WrestlerBioTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_wrestlers_bio_can_format_its_height()
    {
        $bio = factory(WrestlerBio::class)->make(['height' => '73']);

        $this->assertEquals('6\'1"', $bio->formatted_height);
    }
}
