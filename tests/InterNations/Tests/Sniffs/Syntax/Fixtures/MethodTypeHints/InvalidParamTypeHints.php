<?php

class InvalidParamTypeHints
{
    public function indexAction(Request $request, string $context, int $entityType, int $entityId): array
    {
        $entityType = strtoupper($entityType);
        $context = strtoupper($context);

        if (!$this->aclService->canAccessEntityComments($entityType, $entityId)) {
            return [
                'success'  => false,
                'response' => [
                    'nextUrl' => '',
                    'content' => '',
                ],
            ];
        }

        $view = $this->commentReader->read(
            $context,
            $entityType,
            $entityId,
            $request->get('maxResults'),
            $request->get('maxId')
        );

        return [
            'success'  => true,
            'response' => [
                'nextUrl' => $this->getNextUrl($view, $context, $entityType, $entityId),
                'content' => $view->getComments()
            ]
        ];
    }
}