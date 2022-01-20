<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	/**
	 * @Route("/login", name="login", methods={"GET", "POST"})
	 */
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.html.twig', [
			'lastUsername' => $lastUsername,
			'error' => $error,
		]);
	}

	/**
	 * @Route("/logout", name="logout", methods={"GET"})
	 * @throws Exception
	 */
	public function logout(): void
	{
		throw new Exception('This exception should never been thrown');
	}
}
