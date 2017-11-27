<?php
functionCall(
    [
        'foo' => true,
        'bar' => false
    ]
);

$object->methodCall(
    'argument',
    'argument',
    [
        ['foo', 'bar']
    ]
);


$object->methodCall(
    'argument',
    'argument',
    [
        ['foo', 'bar' => 'test', $key => 'value',
                'key' => $value, $value]
    ]
);


functionCall(
    'argument',
    'argument',
    [
        array(
            'foo',
            'bar',
            'baz',
        )
    ]
);

$object->methodCall(
    [
        'argument',
    ]
);


$object->methodCall(
    ['argument']
);

$object->methodCall(
    'argument',
    'argument',
    [
        array('foo', 'bar')
    ]
);
