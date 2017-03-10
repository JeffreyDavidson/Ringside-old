<?php

namespace Tests\Unit;

use App\Wrestler;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WrestlerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_get_formatted_height()
    {
        $wrestler = factory(Wrestler::class)->make([
            'height' => '73'
        ]);

        $this->assertEquals('6\'1"', $wrestler->formatted_height);
    }

}
