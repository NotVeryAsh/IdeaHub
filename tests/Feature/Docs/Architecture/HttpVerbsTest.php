<?php

namespace Tests\Feature\Docs\Architecture;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HttpVerbsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_can_access_http_verbs_page()
    {
        $response = $this->get('/docs/architecture/http-verbs');

        $response->assertStatus(200);
        $response->assertViewIs('docs.architecture.http-verbs.index');
    }

    public function test_http_verb_blade_files_are_included()
    {
        $response = $this->get('/docs/architecture/http-verbs');

        $response->assertSeeInOrder([
            'GET Verb',
            'POST Verb',
            'PATCH Verb',
            'PUT Verb',
            'DELETE Verb',
        ]);
    }
}