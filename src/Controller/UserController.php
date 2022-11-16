<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Utilisateur;

class UserController extends AbstractController
{

    #[Route('/SignUp/{uc}', name: 'app_créercompte')]
    public function créercompte($uc, ManagerRegistry $doctrine): Response
    {
        return $this->render('user/creercompte.html.twig');
    }

    #[Route('/ValidationSignUp', name: 'app_Validercreercompte')]
    public function validercreercompte(ManagerRegistry $doctrine)
    {
        $nom=$_POST["nom"];
        $prenom=$_POST["prenom"];
        $email=$_POST["email"];
        $dateNaissance=$_POST["dateNaissance"];
        $tel=$_POST["tel"];
        $lienportfolio=$_POST["portfolio"];
        $mdp=$_POST["mdp"];
        $user = new Utilisateur();
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setMail($email);
        $user->setDateNaissance(new \DateTime($dateNaissance));
        $user->setTel($tel);
        $user->setLienPortfolio($lienportfolio);
        $user->setPassword(password_hash($mdp,PASSWORD_BCRYPT));
        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_home',); 
    }
}
