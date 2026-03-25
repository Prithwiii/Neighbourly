<?php

use App\Models\Issue;
use App\Models\FakeReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // create a reporter and a sample issue
    $this->reporter = User::factory()->create();
    $this->issue = Issue::create([
        'user_id' => $this->reporter->id,
        'title' => 'Broken streetlight',
        'description' => 'The lamp on 5th avenue has been out for two weeks.',
        'category' => 'Electricity Problem',
        'location' => '5th Avenue and Main',
        // status defaults to under_review via model attributes
    ]);
});

it('allows users to toggle fake reports through the controller', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('issues.report-fake', $this->issue));

    $response->assertRedirect();
    $response->assertSessionHas('info', 'Thanks for reporting! We review these.');

    $this->assertDatabaseHas('fake_reports', [
        'issue_id' => $this->issue->id,
        'user_id' => $user->id,
    ]);

    // toggling again should remove the record
    $response = $this->actingAs($user)
        ->post(route('issues.report-fake', $this->issue));
    $response->assertRedirect();
    $response->assertSessionHas('info', 'Report removed.');

    $this->assertDatabaseMissing('fake_reports', [
        'issue_id' => $this->issue->id,
        'user_id' => $user->id,
    ]);
});

it('allows the reporter to delete their own issue', function () {
    // reporter created in beforeEach
    $response = $this->actingAs($this->reporter)
        ->delete(route('issues.destroy', $this->issue));

    $response->assertRedirect(route('issues.index'));
    $response->assertSessionHas('success', 'Issue deleted.');

    $this->assertDatabaseMissing('issues', ['id' => $this->issue->id]);
});

it('prevents other users from deleting someone else\'s issue', function () {
    $other = User::factory()->create();

    $this->actingAs($other)
        ->delete(route('issues.destroy', $this->issue))
        ->assertStatus(403);

    // ensure issue still exists
    $this->assertDatabaseHas('issues', ['id' => $this->issue->id]);
});
