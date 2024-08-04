<?php

namespace App\Controller;

use App\Utils\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
		$players = $this->api->getCurrentPlayers();
		return $this->render('main/index.html.twig', ['players' => $players]);
	}

	#[Route('/team', name: 'app_team')]
	public function team(): Response
	{
		$players = $this->api->getCurrentPlayers();
		return $this->render('main/team.html.twig', ['players' => $players]);
	}

	#[Route('/contact', name: 'app_contact')]
	public function contact(): Response
	{
		$players = $this->api->getCurrentPlayers();
		return $this->render('main/contact.html.twig', ['players' => $players]);
	}

	#[Route('/@{playerName}', name: 'app_player')]
	public function player(string $playerName): Response
	{
		$player = $this->api->getPlayerFromName($playerName);
		if (gettype($player) !== gettype([])) {
			$player = ['isEmpty' => true, 'name'=>'', 'message' => $player];
		}
		dump($player);
		return $this->render('main/player.html.twig', ['player' => $player]);
	}

	#[Route('/discord', name: 'app_discord')]
	public function discord(): Response
	{
		return $this->redirect('https://discord.gg/qr9QD9ang5');
	}
}
