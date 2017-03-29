<?php

class AttendeeNotificationListener
{
    private $commentPublisher;

    private $commentReader;

    private $aclService;

    private $commentRepository;

    private $commentTypeManager;

    public function __construct(
        CommentPublisher $commentPublisher,
        CommentReader $commentReader,
        AclService $aclService,
        CommentRepository $commentRepository,
        CommentTypeManager $commentTypeManager
    )
    {
        $this->commentPublisher = $commentPublisher;
        $this->commentReader = $commentReader;
        $this->aclService = $aclService;
        $this->commentRepository = $commentRepository;
        $this->commentTypeManager = $commentTypeManager;
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
     *  "/{commentId}",
     *  name="comment_comment_delete",
     *  requirements={"commentId" = "\d+"},
     *  defaults={"_format"="json"}
     * )
     * @Secure(roles="ROLE_USER")
     * @Template("@InterNationsLayout/Formats/jquery.json.twig")
     * @Method("DELETE")
     * @return  Mixed[]
     */
    public function deleteAction(int $commentId): array
    {
        if (!$this->isGenericCsrfTokenValid()) {
            return ['response' => ['success'  => false]];
        }

        $user = $this->getUser();
        /** @var $comment Comment */
        $comment = $this->commentRepository->find($commentId);

        if (!$comment || !$this->aclService->canDeleteComment($user, $comment)) {
            return ['response' => ['success'  => false]];
        }

        try {
            $this->commentPublisher->remove($comment);
        } catch (Exception $e) {
            return ['response' => ['success'  => false]];
        }

        return ['response' => ['success'  => true]];
    }

    /**
     * @Route(
     *  "/{context}/{entityType}/{entityId}",
     *  name="comment_comment_index",
     *  requirements={
     *      "entityId" = "\d+",
     *      "entityType" = "\w+",
     *      "maxId" = "\d+",
     *      "maxResults" = "\d+",
     *      "context" = "\w+"
     *  },
     *  defaults={"_format"="json", "maxResults" = 25}
     * )
     * @Template("@InterNationsLayout/Formats/jquery.json.twig")
     * @Secure(roles="ROLE_USER")
     * @Method("GET")
     * @return Mixed[]
     */
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

    private function getNextUrl(CommentView $view, string $context, int $entityType, int $entityId): string
    {
        if (!$view->isMoreAvailable()) {
            return null;
        }

        return $this->generateUrl(
            'comment_form_index',
            [
                'context'    => strtolower($context),
                'entityType' => strtolower($entityType),
                'entityId'   => $entityId,
                'maxResults' => $this->getRequest()->get('maxResults'),
                'maxId'      => $view->getMaxId(),
            ]
        );
    }

    public function newAction(string $context, int $entityType, int $entityId): Response
    {
        if (!$this->aclService->canAccessEntityComments($entityType, $entityId)) {
            return new Response('');
        }

        return new Response($this->renderForm($context, $entityType, $entityId));
    }

    /**
     * @return Mixed[]
     */
    private function renderForm(string $context, int $entityType, int $entityId): array
    {
        $context = strtoupper($context);
        $entityType = strtoupper($entityType);

        /** @var CommentType $type */
        $type = $this->commentTypeManager->getTypeByName($entityType);

        $parameters = [
            'form'       => $this->createForm(CommentFormType::class)->createView(),
            'user'       => $this->getUser(),
            'context'    => $context,
            'entityId'   => $entityId,
            'entityType' => $entityType,
        ];

        return $this->renderView($type->getContextTemplate('form', $context), $parameters);
    }
}