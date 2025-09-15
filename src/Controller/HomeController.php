<?php

namespace App\Controller;

use MongoDB\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    public function __construct(private Client $mongo) {}

    #[Route('/', name: 'app_home', methods: ['GET','POST'])]
    public function index(Request $request): Response
    {
        $collection = $this->mongo->selectDatabase('blog_auto')->selectCollection('votes');

        if ($request->isMethod('POST')) {
            $token = $request->request->get('_token');
            if (!$this->isCsrfTokenValid('vote', $token)) {
                $this->addFlash('error', 'Jeton CSRF invalide.');
                return $this->redirectToRoute('app_home');
            }

            $selectedCar = $request->request->get('vote');
            if ($selectedCar) {
                $collection->updateOne(['car' => $selectedCar], ['$inc' => ['votes' => 1]], ['upsert' => true]);
                $this->addFlash('success', 'Merci pour votre vote !');
            } else {
                $this->addFlash('error', 'Veuillez sélectionner une voiture.');
            }

            return $this->redirectToRoute('app_home');
        }

        $results = $collection->find([], ['sort' => ['votes' => -1]]);

        return $this->render('home/index.html.twig', [
            'results' => $results,
        ]);
    }
}
