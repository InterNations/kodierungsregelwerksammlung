<?php

class ValidTypeHints
{
    /**
     * @dataProvider provideDataForFeedbackEligibility
     * @param string[] $testCase
     */
    public function testFeedbackEligibility(array $testCase, ?int $x = null): void
    {
        return;
    }

    /**
     * ValidTypeHints constructor.
     * @param int[] $x
     */
    public function __construct(array $x, int $y)
    {
        return;
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

    private function getNextUrl(?float $view, string $context, int $entityType, int $entityId): string
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
     * @return mixed[]
     */
    private static function getNamespace(int $stackPtr, CodeSnifferFile $file): array
    {
        return;
    }

    private static function properMap(callable $f): callable
    {
        return;
    }

    /** @return User|MockObject */
    private function createParticipantMock(): MockObject
    {
        return;
    }
}