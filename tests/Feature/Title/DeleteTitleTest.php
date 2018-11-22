<?php

namespace Tests\Feature\Title;

use App\Models\Title;
use Tests\IntegrationTestCase;

class DeleteTitleTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setupAuthorizedUser('delete-title');
    }

    /** @test */
    public function users_who_have_permission_can_delete_a_title()
    {
        $title = factory(Title::class)->create();

        $response = $this->actingAs($this->authorizedUser)->delete(route('titles.destroy', $title->id));

        $response->assertStatus(302);
        $this->assertSoftDeleted('titles', ['id' => $title->id, 'name' => $title->name]);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_delete_a_title()
    {
        $title = factory(Title::class)->create();

        $response = $this->actingAs($this->unauthorizedUser)->delete(route('titles.destroy', $title->id));

        $response->assertStatus(403);
        $this->assertNull($title->deleted_at);
    }

    /** @test */
    public function guests_cannot_delete_a_title()
    {
        $title = factory(Title::class)->create();

        $response = $this->delete(route('titles.destroy', $title->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertNull($title->deleted_at);
    }

    /** @test */
    public function an_active_title_that_when_deleted_will_redirect_the_user_to_the_active_titles_page()
    {
        $title = factory(Title::class)->states('active')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('active-titles.index'))->delete(route('titles.destroy', $title->id));

        $response->assertRedirect(route('active-titles.index'));
    }

    /** @test */
    public function an_inactive_title_that_when_deleted_will_redirect_the_user_to_the_inactive_titles_page()
    {
        $title = factory(Title::class)->states('inactive')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('inactive-titles.index'))->delete(route('titles.destroy', $title->id));

        $response->assertRedirect(route('inactive-titles.index'));
    }

    /** @test */
    public function a_retired_title_that_when_deleted_will_redirect_the_user_to_the_retired_titles_page()
    {
        $title = factory(Title::class)->states('retired')->create();

        $response = $this->actingAs($this->authorizedUser)->from(route('retired-titles.index'))->delete(route('titles.destroy', $title->id));

        $response->assertRedirect(route('retired-titles.index'));
    }
}
