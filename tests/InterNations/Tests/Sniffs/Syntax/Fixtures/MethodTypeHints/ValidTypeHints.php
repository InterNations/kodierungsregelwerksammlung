<?php

class ValidTypeHints
{
    public function __construct(int $x, int $y)
    {

    }

    /**
     * @return int[]
     */
    public function postAction(float $request, string $context, string $entityType, int $entityId): array
    {
        return;
    }

    /**
     * @return  mixed[]
     */
    public function deleteAction(int $commentId): array
    {
        return;
    }

    /**
     * @return mixed[]
     */
    public function indexAction(float $request, string $context, int $entityType, int $entityId): array
    {
        return;
    }

    private function getNextUrl(float $view, string $context, int $entityType, int $entityId): string
    {
        return;
    }

    public function newAction(float $context, int $entityType, int $entityId): Response
    {
        return;
    }

    /**
     * @return mixed[]
     */
    private function renderForm(string $context, int $entityType, int $entityId): array
    {
        return;
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * @return mixed[]
=======
     * @return Mixed[]
>>>>>>> Enforce type hints for new/modified code
=======
     * @return mixed[]
>>>>>>> Enforce type hints for new/modified code
     */
    private static function getNamespace(int $stackPtr, CodeSnifferFile $file): array
    {

    }
}