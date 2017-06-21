<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ZohoTest extends TestCase
{
	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testZohoapi()
	{
		$response = $this->get('/zohoapi');

		$response->assertStatus(200);
	}

	public function testNotZohoapi()
	{
		$response = $this->get('/asss');

		$response->assertStatus(500);
	}

	public function testaddUserFalse()
	{
		$response = $this->post('/zohoapi/add', [
			'first_name' => 'test',
			'last_name' => 'test2',
			'email' => 'test@a'
		]);
		$response->assertStatus(302);
	}

	public function testaddUser()
	{
		$response = $this->post('/zohoapi/add', [
			'first_name' => 'testTest',
			'last_name' => 'test2Test',
			'email' => 'test@ttest',
			'phone' => '12345678'
		]);
		$this->assertDatabaseHas('contacts', [
			'first_name' => 'testTest',
			'email' => 'test@ttest',
			'phone' => '12345678'
		]);
	}
}