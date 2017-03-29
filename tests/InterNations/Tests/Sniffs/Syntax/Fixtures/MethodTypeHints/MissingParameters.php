<?php

class MissingParameters

{
    /**
     * @Route(
     *  "/{context}/{entityType}/{entityId}",
     *  name="comment_comment_post",
     *  requirements={"entityId" = "\d+", "entityType" = "\w+", "context" = "\w+"},
     *  defaults={"_format"="json"}
     * )
     * @Secure(roles="ROLE_USER")
     * @Template("@InterNationsLayout/Formats/jquery.json.twig")
     * @Method("POST")
     * @return String[]
     */
    public function postAction(Request $request, string $context, string $entityType, int $entityId): array
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

    /**
     * @Route(
     *  "/{context}/{entityType}/{entityId}",
     *  name="comment_comment_post",
     *  requirements={"entityId" = "\d+", "entityType" = "\w+", "context" = "\w+"},
     *  defaults={"_format"="json"}
     * )
     * @Secure(roles="ROLE_USER")
     * @Template("@InterNationsLayout/Formats/jquery.json.twig")
     * @Method("POST")
     * @return String[]
     */
    public function postAction(Request $request, $context, $entityType): array
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