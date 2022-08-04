<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/contact')]
class ContactController extends AbstractController
{

    #[Route('/', name: 'app_contact_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ContactRepository $contactRepository, TranslatorInterface $translator): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->add($contact, true);

            $this->addFlash('success', $translator->trans('message.has_been_sent'));

            return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact/index.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }
}
