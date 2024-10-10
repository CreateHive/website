<?php

namespace App\Controller;

use App\Utils\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
	public function __construct(readonly Api $api)
	{
	}

	#[Route('/', name: 'app_main')]
	public function index(): Response
	{
		return $this->render('main/index.html.twig');
	}

	#[Route('/team', name: 'app_team')]
	public function team(): Response
	{
		return $this->render('main/team.html.twig');
	}

	#[Route('/contact', name: 'app_contact')]
	public function contact(): Response
	{
		return $this->render('main/contact.html.twig');
	}

	#[Route('/@{playerName}', name: 'app_player')]
	public function player(string $playerName): Response
	{
		$player = $this->api->getPlayerFromName($playerName);
		if (gettype($player) !== gettype([])) {
			$player = ['isEmpty' => true, 'name' => '', 'message' => $player];
		}
		return $this->render('main/player.html.twig', ['player' => $player]);
	}

	#[Route('/discord', name: 'app_discord')]
	public function discord(): Response
	{
		return $this->redirect('https://discord.gg/qr9QD9ang5');
	}

	#[Route('/reward/verify-code', name: 'app_reward_verif_code')]
	public function verifyCode(Request $request): Response
	{
		// Récupérer le code envoyé via GET
		$code = $request->query->get('code');
		$secret = $request->query->get('secret');
		if ($secret!= 'CF9DB9C626FBDA53DD13A43C4ECA6') {
			echo 'invalid';
			exit;
		}

		$filePath = __DIR__ . '/../../var/code_mois.txt';

		if (!file_exists($filePath)) {
			echo 'invalid';
			exit;
		}

		$codeFromFile = trim(file_get_contents($filePath));

		if ($code === $codeFromFile) {
			echo 'valid';
			exit;
		}
		echo 'invalid';
		exit;
	}
}
