<?php

namespace Tests\Unit;

use App\Exceptions\WrestlerNotInjuredException;
use App\Exceptions\WrestlerCannotBeInjuredException;
use App\Models\Wrestler;
use App\Models\Manager;
use App\Models\Title;
use App\Models\WrestlerStatus;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WrestlerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_have_matches()
    {
        $wrestler = factory(Wrestler::class)->states('active')->create();

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $wrestler->matches);
    }

    /** @test */
    public function a_wrestlers_bio_can_format_its_height()
    {
        $bio = factory(Wrestler::class)->make(['height' => '73']);

        $this->assertEquals('6\'1"', $bio->height);
    }

}
