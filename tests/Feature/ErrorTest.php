<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */

    protected static $addedId;
    protected static $allDayId;

    // Test start page
    public function testStart()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSessionMissing('shared_error');
        $response->assertSessionMissing('shared_message');
    }


    // Test for adding event, tests redirect and presence of error
    public function testAdd()
    {
        $response = $this->withHeaders([])->json('POST', '/add', [
            'name' => 'Test Event',
            'date' => '2020-10-31',
            'time' => '15:00',
            'end' => '19:00',
            'allday' => 'off',

        ]);

        self::$addedId = session('added_id');


        $response->assertRedirect('/');
        $response->assertSessionHas('shared_error', false);
    }

    // Add an all day event
    public function testAddAllDayEvent()
    {
        $response = $this->withHeaders([])->json('POST', '/add', [
            'name' => 'All day event',
            'date' => '2020-10-27',
            'allday' => 'on',

        ]);

        self::$allDayId = session('added_id');


        $response->assertRedirect('/');
        $response->assertSessionHas('shared_error', false);
    }

    public function testUpdateEqualAllDayEvent()
    {
        $response = $this->withHeaders([])->json('POST', '/update', [
            'id' => self::$allDayId,
            'name' => 'All day event',
            'date' => '2020-10-27',
            'allday' => 'on',

        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('shared_error', true);
    }


    public function testUpdateAllDayEvent()
    {
        $response = $this->withHeaders([])->json('POST', '/update', [
            'id' => self::$allDayId,
            'name' => 'All day event (updated)',
            'date' => '2020-10-27',
            'allday' => 'on',

        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('shared_error', false);
    }

    public function testUpdateAllDayEventToNormalEvent()
    {
        $response = $this->withHeaders([])->json('POST', '/update', [
            'id' => self::$allDayId,
            'name' => 'All day event (updated to normal)',
            'date' => '2020-10-27',
            'start' => '10:00',
            'end' => '15:00',
            'allday' => 'off',

        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('shared_error', false);
    }


    // Test updating an equal event
    public function testUpdateEqualEvent()
    {
        $response = $this->withHeaders([])->json('POST', '/update', [
            'id' => self::$addedId,
            'name' => 'Test Event',
            'date' => '2020-10-31',
            'start' => '15:00',
            'end' => '19:00',
            'allday' => 'off',

        ]);

        $response->assertRedirect('/');

        $response->assertSessionHas('shared_error', true);
    }

    // Test updating an event with changed information
    public function testUpdate()
    {
        $response = $this->withHeaders([])->json('POST', '/update', [
            'id' => self::$addedId,
            'name' => 'Updated name',
            'date' => '2020-10-31',
            'start' => '15:00',
            'end' => '19:00',
            'allday' => 'off',

        ]);
        $response->assertRedirect('/');

        $response->assertSessionHas('shared_error', false);
    }


    // Delete event
    public function testDelete()
    {
        $response = $this->withHeaders([])->json('POST', '/delete', [
            'id' => self::$addedId,
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('shared_error', false);
    }

    public function testDeleteAllDayEvent()
    {
        $response = $this->withHeaders([])->json('POST', '/delete', [
            'id' => self::$allDayId,
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('shared_error', false);
    }

    // Delete the same event after it has already been deleted
    public function testDeleteMissingId()
    {
        $response = $this->withHeaders([])->json('POST', '/delete', [
            'id' => self::$addedId,
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('shared_error', true);
    }
}
