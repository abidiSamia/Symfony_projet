<?php

namespace App\Controller\Admin;

use App\Entity\Option1;
use App\Form\Option1Type;
use App\Repository\OptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/option")
 */
class AdminOptionController extends AbstractController
{
    /**
     * @Route("/", name="admin.option.index", methods="GET")
     */
    public function index(OptionRepository $optionRepository): Response
    {
        return $this->render('admin/option/index.html.twig', ['options' => $optionRepository->findAll()]);
    }

    /**
     * @Route("/new", name="admin.option.new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $option = new Option1();
        $form = $this->createForm(Option1Type::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($option1);
            $em->flush();

            return $this->redirectToRoute('admin.option.index');
        }

        return $this->render('admin/option/new.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.option.edit", methods="GET|POST")
     */
    public function edit(Request $request, Option1 $option): Response
    {
        $form = $this->createForm(Option1Type::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.option.edit', ['id' => $option->getId()]);
        }

        return $this->render('admin/option/edit.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.option.delete", methods="DELETE")
     */
    public function delete(Request $request, Option1 $option): Response
    {
        if ($this->isCsrfTokenValid('admin/delete'.$option->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($option);
            $em->flush();
        }

        return $this->redirectToRoute('admin.option.index');
    }
}
