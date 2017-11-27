<?php

trait ValidTypeHintsTrait
{

    /** @param string[] $roleNames */
    abstract protected function hasAnyRole(array $roleNames): bool;
}
