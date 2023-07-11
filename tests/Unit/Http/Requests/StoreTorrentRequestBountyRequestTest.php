<?php

beforeEach(function (): void {
    $this->subject = new \App\Http\Requests\StoreTorrentRequestBountyRequest();
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
        'seedbonus' => [
            'required',
            'numeric',
            'min:100',
        ],
        'anon' => [
            'required',
            'boolean',
        ],
    ], $actual);
});

// test cases...
