<?php

class WrongTypeHint

{
    public function __call(Request $request, $test): array
    {
        $form = $this->createForm(CommentFormType::class);
        $form->handleRequest($request);

        $entityType = strtoupper($entityType);
        $context = strtoupper($context);

        if (!$this->aclService->canAccessEntityComments($entityType, $entityId) || !$form->isValid()) {
            return [
                'response' => [
                    'success'  => false,
                    'formHtml' => $this->renderForm($context, $entityType, $entityId),
                ]
            ];
        }

        /** @var $comment Comment */
        $comment = $form->getData();
        $this->commentPublisher->publish($entityType, $entityId, $comment, $context);

        return [
            'response' => [
                'success'  => true,
                'content'  => [
                    'entry' => $this->commentReader->renderComment($context, $entityType, $comment),
                    'form'  => $this->renderForm($context, $entityType, $entityId),
                ]
            ]
        ];
    }
}