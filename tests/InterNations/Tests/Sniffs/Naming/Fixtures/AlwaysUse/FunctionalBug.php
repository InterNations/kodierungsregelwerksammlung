<?php
namespace Foo;

use Functional as F;

F\invoke_if(F\invoke_if($obj, 'getFoo'), 'getBar');
