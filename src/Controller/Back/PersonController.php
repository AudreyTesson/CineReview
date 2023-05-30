<?php

namespace App\Controller\Back;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/person", name="app_back_person_")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(PersonRepository $personRepository): Response
    {
        return $this->render('back/person/index.html.twig', [
            'people' => $personRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, PersonRepository $personRepository): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personRepository->add($person, true);
            $this->addFlash("success", "Votre Person a bien été ajouté.");

            return $this->redirectToRoute('app_back_person_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/person/new.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(?Person $person): Response
    {
        if ($person === null){throw $this->createNotFoundException("cette personne n'existe pas");}
        return $this->render('back/person/show.html.twig', [
            'person' => $person,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ?Person $person, PersonRepository $personRepository): Response
    {
        if ($person === null){throw $this->createNotFoundException("cette personne n'existe pas");}
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personRepository->add($person, true);

            return $this->redirectToRoute('app_back_person_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/person/edit.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, ?Person $person, PersonRepository $personRepository): Response
    {
        if ($person === null){throw $this->createNotFoundException("cette personne n'existe pas");}
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
            $personRepository->remove($person, true);
        }

        return $this->redirectToRoute('app_back_person_index', [], Response::HTTP_SEE_OTHER);
    }
}
