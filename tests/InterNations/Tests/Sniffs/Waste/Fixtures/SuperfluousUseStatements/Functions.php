<?php
use function Functional\true;
use function Functional\select;
use function Functional\reject;
use function Functional\false;
use Functional as F;

reject([1, 2, 3], 'callback');
false([true, true, true]);

$f = function () use ($var) {
};
