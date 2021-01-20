<?php

namespace App\Controller;

use DateTimeZone;
use App\Entity\Offer;
use App\Entity\Project;
use App\Form\CreateOfferType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OfferController extends AbstractController
{
    /**
     * @Route("/soumettre_offre/{id}", name="offer_create")
     */
    public function create($id, Request $req, EntityManagerInterface $emi): Response
    {
        $offerMaker = $this->getUser();
        $projectRepo = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepo->findOneBy(['id' => $id]);
        $projectUserId = (int)$project->getUserId()->getId();
        $offer = new Offer();

        $offerForm = $this->createForm(CreateOfferType::class, $offer);
        $offerForm->handleRequest($req);

        if ($this->getUser() && $this->getUser()->getId() != $projectUserId) {
            if ($offerForm->isSubmitted() && $offerForm->isValid()) {
                $offer->setCreateDate(date_create("now", new DateTimeZone("Europe/Paris")));
                $offer->setProject($project);
                $offer->setUserId($offerMaker);
                $emi->persist($offer);
                $emi->flush();



                $this->addFlash('success', 'Votre proposition a été transmise à ' . $project->getUserId()->getUsername());
                return $this->redirectToRoute('offer_details', ['id' => $offer->getId()]);
            }
        } else {
            $this->addFlash('warning', 'No ! you know you can\'t do that ! don\'t you ?');
        }
        return $this->render('offer/create.html.twig', [
            'project' => $project,
            'offerForm' => $offerForm->createView()
        ]);
    }

    /**
     * @Route("/details_offre/{id}", name="offer_details", requirements = {"id": "\d+"})
     */
    public function details($id): Response
    {
        $user = $this->getUser();
        $offer = $this->getDoctrine()->getRepository(Offer::class)->find(['id' => $id]);

        return $this->render('offer/details.html.twig', [
            'offer' => $offer,
            'user' => $user
        ]);
    }

    /**
     * @Route("/mes_offres", name="offer_ownOffer")
     */
    public function ownOffers(): Response
    {
        return $this->render('offer/ownOffer.html.twig', []);
    }
}
