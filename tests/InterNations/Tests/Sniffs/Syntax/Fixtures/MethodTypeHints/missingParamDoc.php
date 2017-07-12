<?php

class missingParamDoc
{
    public function x(array $a, string $b): bool
    {
        return;
    }

    public function y(iterable $a, string $b): bool
    {
        return;
    }

    public function z(int $a, Collection $b): bool
    {
        return;
    }

    /** @param int[] $a */
    public function o(array $a, string $b): bool
    {
        return;
    }

    /** @param string[] $a */
    public function p(iterable $a, string $b): bool
    {
        return;
    }

    /** @param Collection|Browser[] $b */
    public function q(int $a, Collection $b): bool
    {
        return;
    }
}