<?php

namespace Tests\Feature\Docs\Architecture;

use Tests\TestCase;

class HttpVerbsTest extends TestCase
{
    public function test_it_can_access_http_verbs_page()
    {
        $response = $this->get('/docs/architecture/http-verbs');

        $response->assertStatus(200);
        $response->assertViewIs('docs.architecture.http-verbs.index');
    }
}
