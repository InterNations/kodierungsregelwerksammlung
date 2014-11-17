<?php
$object->methodCall("
foo
bar");

$object->methodCall('
foo
bar');

StaticClass::methodCall(<<<EOS
foo
bar
EOS
);

StaticClass::methodCall(<<<'EOS'
foo
bar
EOS
);

functionCall(
    'Comment' // With comment
);


functionCall(
    'Comment' # With comment
);

functionCall(
    'Comment' /* With comment */
);

functionCall(
    'Comment' /** With comment */
);
