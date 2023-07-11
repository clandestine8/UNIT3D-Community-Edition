<?php

beforeEach(function (): void {
    $this->subject = new \App\Http\Requests\UpdateTorrentRequestRequest();
});

test('authorize', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $actual = $this->subject->authorize();

    $this->assertTrue($actual);
});

test('rules', function (): void {
    $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

    $actual = $this->subject->rules();

    $this->assertValidationRules([
        'name' => [
            'required',
            'max:180',
        ],
        'imdb' => [
            'required',
            'numeric',
        ],
        'tvdb' => [
            'required',
            'numeric',
        ],
        'tmdb' => [
            'required',
            'numeric',
        ],
        'mal' => [
            'required',
            'numeric',
        ],
        'igdb' => [
            'required',
            'numeric',
        ],
        'category_id' => [
            'required',
            'exists:categories,id',
        ],
        'type_id' => [
            'required',
            'exists:types,id',
        ],
        'resolution_id' => [
            'nullable',
            'exists:resolutions,id',
        ],
        'description' => [
            'required',
            'string',
        ],
        'anon' => [
            'required',
            'boolean',
        ],
    ], $actual);
});

// test cases...
