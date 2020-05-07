<?php
$noSpace = static function() {
};

$tooManySpaces = static function  () {
};

$useNoSpace = static function ()use($foo) {
};

$useTooManySpaces1 = static function ()  use($foo) {
};

$useTooManySpaces2 = static function ()  use ($foo) {
};

$openingBraceNextLine = static function ()
{};
