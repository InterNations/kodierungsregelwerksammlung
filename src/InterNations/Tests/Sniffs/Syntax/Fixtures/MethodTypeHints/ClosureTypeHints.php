<?php

class ClosureTypeHintss
{
    public static function invalidClosure(int $x, ?int $y, bool $z): ?string
    {
        return array_map(
            static function ($someVar) {
                return true;
            },
            $someArray
        );
    }

    public static function validClosure(): void
    {
        array_map(
            static function (int $someVar): bool {
                return true;
            },
            $someArray
        );
    }

    public static function validClosureWithparam(): void
    {
        array_map(
            static function (int $someVar) use ($user): bool {
                return true;
            },
            $someArray
        );
    }
}