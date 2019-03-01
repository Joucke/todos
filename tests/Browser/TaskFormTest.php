<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TaskFormTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_a_checkbox_component_for_period()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_has_a_checkbox_component_for_optional()
    {
        $this->markTestIncomplete();
    }
}
