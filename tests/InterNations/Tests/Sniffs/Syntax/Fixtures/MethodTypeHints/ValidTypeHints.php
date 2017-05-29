<?php

class ValidTypeHints
{
    /** @dataProvider provideDataForFeedbackEligibility */
    public function testFeedbackEligibility(array $testCase): void
    {
    }

    /**
     * ValidTypeHints constructor.
     * @param int[] $x
     */
    public function __construct(array $x, int $y)
    {
    }

    /**
     * @return int[]
     */
    public function postAction(float $request, string $context, string $entityType, int $entityId): array
    {
    }

    /**
     * @return  mixed[]
     */
    public function deleteAction(int $commentId): array
    {
    }

    /**
     * @return mixed[]
     */
    public function indexAction(float $request, string $context, int $entityType, int $entityId): array
    {
    }

    private function getNextUrl(?float $view, string $context, int $entityType, int $entityId): string
    {
    }

    public function newAction(float $context, int $entityType, int $entityId): Response
    {
    }

    /**
     * @return mixed[]
     */
    private function renderForm(string $context, int $entityType, int $entityId): array
    {
    }

    /**
     * @return mixed[]
     */
    private static function getNamespace(int $stackPtr, CodeSnifferFile $file): array
    {
    }

    private static function properMap(callable $f): callable
    {
    }
}