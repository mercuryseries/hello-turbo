<?php

namespace App\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function new(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank,
                    new Length(['min' => 2]),
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank,
                    new Email,
                ]
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank,
                    new Length(['min' => 10]),
                ]
            ])
            ->getForm()
        ;

        return $this->handleForm(
            $form,
            $request,
            onSuccess: function (FormInterface $form, array $data) {
                dump(sprintf('Incoming email from %s <%s>', $data['name'], $data['email']));

                $this->addFlash('success', "Message sent! We'll get back to you very soon.");

                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            },
            render: function (FormInterface $form, ?array $data) {
                return $this->render('messages/new.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        );
    }
}
