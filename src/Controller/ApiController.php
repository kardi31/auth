<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\UserType;
use App\Message\CreateUserMessage;
use App\Message\EditUserMessage;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/add", name="user_add", methods={"POST"})
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $form = $this->createForm(UserType::class);
        $form->submit($data);

        if ($form->isValid()) {
            $user = $form->getData();
            $this->messageBus->dispatch(new CreateUserMessage($user, $form->get('password')->getData()));

            return new JsonResponse(
                [
                    'status' => 'success'
                ]
            );
        }

        return new JsonResponse(
            [
                'status' => 'error',
                'message' => (string)$form->getErrors(true, false),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/edit/{id}", name="user_edit", methods={"PATCH"})
     * @param Request $request
     * @param int $id
     * @param UserRepository $userRepository
     *
     * @return JsonResponse
     */
    public function edit(Request $request, int $id, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        if (!$user = $userRepository->find($id)) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => 'Invalid user ID',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);

        if ($form->isValid()) {
            $user = $form->getData();
            $this->messageBus->dispatch(new EditUserMessage($user, $data['password']));

            return new JsonResponse(
                [
                    'status' => 'success'
                ]
            );
        }

        return new JsonResponse(
            [
                'status' => 'error',
                'message' => (string)$form->getErrors(true, false),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/show/{id}", name="user_show", methods={"GET"})
     * @param int $id
     * @param UserRepository $userRepository
     *
     * @return JsonResponse
     */
    public function show(int $id, UserRepository $userRepository): JsonResponse
    {
        if (!isset($id) || !$user = $userRepository->find($id)) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => 'Invalid user ID',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse($user);
    }

    /**
     * @Route("/delete/{id}", name="user_delete", methods={"DELETE"})
     * @param int $id
     * @param UserRepository $userRepository
     *
     * @return JsonResponse
     */
    public function delete(int $id, UserRepository $userRepository): JsonResponse
    {
        if (!isset($id) || !$user = $userRepository->find($id)) {
            return new JsonResponse(
                [
                    'status' => 'error',
                    'message' => 'Invalid user ID',
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $userRepository->delete($user);

        return new JsonResponse(
            [
                'status' => 'success'
            ]
        );
    }
}
