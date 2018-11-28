<?php

namespace Tests\Feature\Title;

use App\Models\Title;
use Tests\IntegrationTestCase;

class EditTitleTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser(['update-title']);
    }

    /** @test */
    public function users_who_have_permission_can_view_the_edit_title_page()
    {
        $title = factory(Title::class)->create();

        $response = $this->actingAs($this->authorizedUser)->get(route('titles.edit', $title->id));

        $response->assertSuccessful();
        $response->assertViewIs('titles.edit');
        $response->assertViewHas('title');
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_the_edit_title_page()
    {
        $title = factory(Title::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->get(route('titles.edit', $title->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_the_edit_title_page()
    {
        $title = factory(Title::class)->create();

        $response = $this->get(route('titles.edit', $title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
