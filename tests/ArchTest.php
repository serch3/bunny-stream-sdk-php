<?php

test('globals')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

test('strict types')
    ->expect('Bunny\Stream')
    ->toUseStrictTypes();

test('contracts')
    ->expect('Bunny\Stream\API')
    ->toBeClasses()
    ->toExtend('Bunny\Stream\API\AbstractApi');
