<?php

class ValidTypeHints
{
    public function __construct(int $x, int $y)
    {

    }

    /**
     * @return Integer[]
     */
    public function postAction(float $request, string $context, string $entityType, int $entityId): array
    {
       return;
    }

    /**
     * @return  Mixed[]
     */
    public function deleteAction(int $commentId): array
    {
        return;
    }

    /**
     * @return Mixed[]
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
     * @return Mixed[]
     */
    private function renderForm(string $context, int $entityType, int $entityId): array
    {
        return;
    }
}