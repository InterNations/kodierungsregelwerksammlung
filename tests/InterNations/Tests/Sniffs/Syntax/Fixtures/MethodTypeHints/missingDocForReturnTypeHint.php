<?php

class missingDocForReturnTypeHint
{
    public function x(int $a, string $b): array
    {
        return;
    }

    public function y(int $a, string $b): iterable
    {
        return;
    }

    public function z(int $a, bool $b): Collection
    {
        return;
    }

    /**
     * @param int[] $a
     * @return string[]
     */
    public function o(array $a, string $b): array
    {
        return;
    }

    /**
     * @param string[] $a
     * @return int[]
     */
    public function p(iterable $a, string $b): iterable
    {
        return;
    }

    /**
     * @param Collection|Browser[] $b
     * @return Collection|User[]
     */
    public function q(int $a, Collection $b): Collection
    {
        return;
    }
}