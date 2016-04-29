<?php
valid4(true !== false ? (1 + 2) : (3 + 4));
$valid1 = true ? 1 : 0;
$valid2 = true !== false ? 1 : 0;
$valid3 = true !== false && false === false ? 1 : 0;
$valid4 = (true !== false && false === false) ? 1 : 0;
$valid5 = func() ? true : false;
$weirdButOk2 += (true !== false) ? 1 : 0;
func(($foo == $bar) ? 1 : 0);

$invalid2 = true ?1 : 0;
$invalid3 = true? 1  :  0;
$invalid4 = func()? 1  :  0;
$invalid5 = $this->method()? 1 :0;
$invalid6 = func($this->method())?1:0;
$invalid7 = $this->method($this->method())?1:0;
$invalid8 = $value ? :  ($value = true);

$valid6 = true ?: false;
$valid7 = $value ?: ($value = true);

$invalidMulti = true ? true ? false : false : false;
