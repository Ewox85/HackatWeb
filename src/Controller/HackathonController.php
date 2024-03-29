<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Hackathon;
use Doctrine\Persistence\ManagerRegistry;

class HackathonController extends AbstractController
{
    #[Route('/hackathon', name: 'app_listeHackathon')]
    public function afficheList(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Hackathon::class); #recuperation du repository des Hackathons
        // liste hackathon = [[infoHackathon : hackathon, place dispo : nb], [infoHackathon : hackathon, place dispo : nb], ... ]
        $listeHackat = [];
        foreach ($repository->findAll() as $hackathon ){
            $listeHackat[] = array("infoHackathon" => $hackathon, "placesDispo" => ($hackathon->getNbPlaces() - $hackathon->getLesInscriptions()->count()));
        }

        $tabFavoris = array();

        #On recherhe si un utilisateur est connecté
        $user = $this->getUser();

        if($user != null)
        {  
            #On recherche si l'utilisateur connecté a mit ou non ce hackhaton en favoris en parcourant la liste des favoris (liste de Hackathons)
            $lesFavoris=$user->getFavoris();
            foreach($lesFavoris as $unFavori)
            {
                #Si le favori existe, on passe estFavoris en true pour afficher un boutton pour supprimer de ses favoris (voir twig)
                $id = $unFavori->getId();
                $tabFavoris[] += $id;
            }

        }
        
        return $this->render('hackathon/listeHackathon.html.twig', [
            'lesHackathons' => $listeHackat,"tabFavoris" => $tabFavoris #on récupère tout les hackathons qu'on passera en param lors du rendu + un tableau avec les id des favoris
        ]);
    }

    #[Route('/hackathon/{id}', name: 'app_informationhackathon')]
    public function afficheInfo($id, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Hackathon::class);
        $leHackathon = $repository->find($id);
        $placesDispo = $leHackathon->getNbPlaces() - $leHackathon->getLesInscriptions()->count();
        $estFavoris = false;
        $lesFavoris = null;
        #On recherhe si un utilisateur est connecté
        $user = $this->getUser();
        if($user != null)
        {
            
            #On recherche si l'utilisateur connecté a mit ou non ce hackhaton en favoris en parcourant la liste des favoris (liste de Hackathons)
            $lesFavoris=$user->getFavoris();
            foreach($lesFavoris as $unFavori)
            {
                #Si le favori existe, on passe estFavoris en true pour afficher un boutton pour supprimer de ses favoris (voir twig)
                if($unFavori->getId() == $id)
                {
                    $estFavoris = true;
                }
            }
        }
        return $this->render('hackathon/information.html.twig', [
            'leHackaton' => $leHackathon, "placesDispo" => $placesDispo, "estFavoris" => $estFavoris, "listeFavs" => $lesFavoris
        ]);
    }
}
