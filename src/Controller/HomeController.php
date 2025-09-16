<?php

namespace App\Controller;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    private const CARS = [
        'Nissan Skyline GT-R',
        'Toyota Supra',
        'Mustang GT',
        'Mitsubishi Lancer Evo',
        'BMW M3',
        'Porsche 911',
    ];

    private const COOKIE_NAME = 'voter_id';

    public function __construct(private Client $mongo) {}

    #[Route('/', name: 'app_home', methods: ['GET','POST'])]
    public function index(Request $request): Response
    {
        // GET: juste afficher la page (et poser le cookie si absent)
        if (!$request->isMethod('POST')) {
            $resp = $this->render('home/index.html.twig');
            $this->ensureVoterCookie($request, $resp);
            return $resp;
        }

        // POST fallback (si JS désactivé)
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('vote', $token)) {
            $this->addFlash('error', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('app_home', ['_fragment' => 'vote']);
        }

        $selected = $request->request->get('vote');
        if (!$selected) {
            $this->addFlash('error', 'Veuillez sélectionner une voiture.');
            return $this->redirectToRoute('app_home', ['_fragment' => 'vote']);
        }

        $db        = $this->mongo->selectDatabase('blog_auto');
        $votesColl = $db->selectCollection('votes');
        $voters    = $db->selectCollection('voters');

        // S’assure que l’index d’unicité existe (safe même si déjà créé)
        $voters->createIndex(['voterId' => 1], ['unique' => true]);

        // Récupère/pose le voter_id (cookie)
        $voterId = $this->getOrCreateVoterId($request);

        // Déjà voté ?
        if ($voters->findOne(['voterId' => $voterId])) {
            $this->addFlash('error', 'Vous avez déjà voté.');
            return $this->redirectToRoute('app_home', ['_fragment' => 'vote']);
        }

        // Enregistre le vote + marque le votant comme “utilisé”
        $votesColl->updateOne(['car' => $selected], ['$inc' => ['votes' => 1]], ['upsert' => true]);
        $voters->insertOne([
            'voterId' => $voterId,
            'choice'  => $selected,
            'at'      => new UTCDateTime(),
            'ip'      => $request->getClientIp(),
            'ua'      => substr((string) $request->headers->get('User-Agent'), 0, 255),
        ]);

        $this->addFlash('success', 'Merci pour votre participation !');

        // Pose le cookie si besoin au moment du redirect
        $resp = $this->redirectToRoute('app_home', ['_fragment' => 'vote']);
        $this->ensureVoterCookie($request, $resp);
        return $resp;
    }

    #[Route('/vote/submit', name: 'app_vote_ajax', methods: ['POST'])]
    public function voteAjax(Request $request): JsonResponse
    {
        $db        = $this->mongo->selectDatabase('blog_auto');
        $votesColl = $db->selectCollection('votes');
        $voters    = $db->selectCollection('voters');
        $voters->createIndex(['voterId' => 1], ['unique' => true]);

        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('vote', $token)) {
            return $this->json(['ok' => false, 'message' => 'Jeton CSRF invalide.'], 400);
        }

        $selected = $request->request->get('vote');
        if (!$selected) {
            return $this->json(['ok' => false, 'message' => 'Veuillez sélectionner une voiture.'], 400);
        }

        $voterId = $this->getOrCreateVoterId($request);

        // déjà voté ?
        if ($voters->findOne(['voterId' => $voterId])) {
            $resp = $this->json(['ok' => false, 'message' => 'Vous avez déjà voté.'], 409);
            // Pose le cookie au cas où il manquait
            $this->ensureVoterCookie($request, $resp);
            return $resp;
        }

        // Enregistre vote + marquage votant
        $votesColl->updateOne(['car' => $selected], ['$inc' => ['votes' => 1]], ['upsert' => true]);
        $voters->insertOne([
            'voterId' => $voterId,
            'choice'  => $selected,
            'at'      => new UTCDateTime(),
            'ip'      => $request->getClientIp(),
            'ua'      => substr((string) $request->headers->get('User-Agent'), 0, 255),
        ]);

        $results = $this->getRanking($votesColl);

        $resp = $this->json([
            'ok'      => true,
            'message' => 'Merci pour votre participation !',
            'total'   => $results['total'],
            'items'   => $results['items'], // [{label, votes}] triés desc
        ]);
        $this->ensureVoterCookie($request, $resp);
        return $resp;
    }

    /** Classement simple : total + items triés par nb de votes décroissant */
    private function getRanking(Collection $votesColl): array
    {
        // Récupère les comptes actuels
        $counts = [];
        foreach ($votesColl->find([]) as $doc) {
            $car = (string)($doc['car'] ?? '');
            $counts[$car] = (int)($doc['votes'] ?? 0);
        }

        // Assure l’existence de toutes les voitures (0 si absentes)
        $items = [];
        $total = 0;
        foreach (self::CARS as $car) {
            $v = $counts[$car] ?? 0;
            $items[] = ['label' => $car, 'votes' => $v];
            $total += $v;
        }

        // Tri décroissant
        usort($items, fn ($a, $b) => $b['votes'] <=> $a['votes']);

        return ['total' => $total, 'items' => $items];
    }

    /** Lit le cookie ou génère un ID anonyme si absent (non persistant tant qu’on ne l’ajoute pas à la réponse) */
    private function getOrCreateVoterId(Request $request): string
    {
        $id = (string) $request->cookies->get(self::COOKIE_NAME, '');
        if ($id !== '') {
            return $id;
        }
        // ID aléatoire base16 (32 chars)
        return bin2hex(random_bytes(16));
    }

    /** Pose le cookie dans la réponse si le navigateur n’en a pas encore */
    private function ensureVoterCookie(Request $request, Response $response): void
    {
        if ($request->cookies->has(self::COOKIE_NAME)) {
            return;
        }
        $id = $this->getOrCreateVoterId($request);
        $cookie = Cookie::create(self::COOKIE_NAME, $id, (new \DateTime('+1 year')))
            ->withHttpOnly()
            ->withSecure(false) // passe à true si HTTPS
            ->withPath('/')
            ->withSameSite('Lax');
        $response->headers->setCookie($cookie);
    }
}
