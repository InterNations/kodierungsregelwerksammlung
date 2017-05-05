<?php
function methodCall1(
    $arg = '222222222222222222222222222222222',
    $arg2 = 111111111111111111111111111111111111111
): string {
    return "hi";
}

function methodCall2(
    $arg = '000000000000000000000000000000000',
    $arg2 = 111111111111111111111111111111111111111
): ?string {
    return "hi";
}

function methodCall3(
    $arg = '333333333333333333333333333333333',
    $arg2 = 111111111111111111111111111111111111111
): ?Clazz {
    return "hi";
}


function methodCall4(
    $arg = '444444444444444444444444444444444',
    $arg2 = 555555555555555555555555555555555555
): ?Clazz {
    return "hi";
}

public function methodCall5(): ?string
{
    return $this->getId() ? (string) $this->getId() : null;
}

public function methodCall6(): ?Clazz
{
    return "hi";
}

switch (true) {
    case $this->somePropertyName->someMethodInvocation():
        break;
}
