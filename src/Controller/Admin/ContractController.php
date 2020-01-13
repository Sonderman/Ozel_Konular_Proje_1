<?php

namespace App\Controller\Admin;

use App\Entity\Contract;
use App\Form\ContractType;
use App\Repository\ContractRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/contract")
 */
class ContractController extends AbstractController
{
    /**
     * @Route("/{slug}", name="admin_contract_index", methods={"GET"})
     */
    public function index(ContractRepository $contractRepository,$slug): Response
    {

        $contracts =$contractRepository->getContracts($slug);
        //dump($contracts);
        //die();
        return $this->render('admin/contract/index.html.twig', [
            'contracts' =>$contracts,
        ]);
    }

    /**
     * @Route("/new", name="admin_contract_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contract = new Contract();
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contract);
            $entityManager->flush();

            return $this->redirectToRoute('admin_contract_index');
        }

        return $this->render('admin/contract/new.html.twig', [
            'contract' => $contract,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_contract_show", methods={"GET"})
     */
    public function show(Contract $contract): Response
    {
        return $this->render('admin/contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_contract_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contract $contract): Response
    {
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $status=$form['status']->getData();
            return $this->redirectToRoute('admin_contract_index',['slug'=>$status]);
        }
        //dump($contract);
       // die();
        return $this->render('admin/contract/edit.html.twig', [
            'contract' => $contract,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_contract_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Contract $contract): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contract->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contract);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_contract_index');
    }
}
