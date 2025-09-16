<?php

namespace App\Controller;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\BSON\UTCDateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    /** Les 6 choix proposés */
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

    /* ---------- Helpers ENV -> DB / Collections (défauts pour le local) ---------- */

    private function dbName(): string
    {
        return $_ENV['MONGODB_DB'] ?? 'blog_auto';
    }

    private function collVotesName(): string
    {
        return $_ENV['MONGODB_COLLECTION_VOTES'] ?? 'votes';
    }

    private function collVotersName(): string
    {
        return $_ENV['MONGODB_COLLECTION_VOTERS'] ?? 'voters';
    }

    private function db(): Database
    {
        return $this->mongo->selectDatabase($this->dbName());
    }

    private function votesColl(): Collection
    {
        return $this->db()->selectCollection($this->collVotesName());
    }

    private function votersColl(): Collection
    {
        return $this->db()->selectCollection($this->collVotersName());
    }

    /* ---------------------------------- Routes ---------------------------------- */

    #[Route('/', name: 'app_home', methods: ['GET','POST'])]
    public function index(Request $request): Response
    {
        // GET : affiche la page et pose le cookie si absent
        if (!$request->isMethod('POST')) {
            $resp = $this->render('home/index.html.twig');
            $this->ensureVoterCookie($request, $resp);
            return $resp;
        }

        // Fallback POST (si JS désactivé)
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

        $votesColl = $this->votesColl();
        $voters    = $this->votersColl();

        // Index d’unicité “voterId”
        $voters->createIndex(['voterId' => 1], ['unique' => true]);

        $voterId = $this->getOrCreateVoterId($request);

        if ($voters->findOne(['voterId' => $voterId])) {
            $this->addFlash('error', 'Vous avez déjà voté.');
            return $this->redirectToRoute('app_home', ['_fragment' => 'vote']);
        }

        // Vote + marquage du votant
        $votesColl->updateOne(['car' => $selected], ['$inc' => ['votes' => 1]], ['upsert' => true]);
        $voters->insertOne([
            'voterId' => $voterId,
            'choice'  => $selected,
            'at'      => new UTCDateTime(),
            'ip'      => $request->getClientIp(),
            'ua'      => substr((string) $request->headers->get('User-Agent'), 0, 255),
        ]);

        $this->addFlash('success', 'Merci pour votre participation !');

        $resp = $this->redirectToRoute('app_home', ['_fragment' => 'vote']);
        $this->ensureVoterCookie($request, $resp);
        return $resp;
    }

    #[Route('/vote/submit', name: 'app_vote_ajax', methods: ['POST'])]
    public function voteAjax(Request $request): JsonResponse
    {
        $votesColl = $this->votesColl();
        $voters    = $this->votersColl();
        $voters->createIndex(['voterId' => 1], ['unique' => true]);

        // CSRF
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('vote', $token)) {
            return $this->json(['ok' => false, 'message' => 'Jeton CSRF invalide.'], 400);
        }

        // Choix
        $selected = $request->request->get('vote');
        if (!$selected) {
            return $this->json(['ok' => false, 'message' => 'Veuillez sélectionner une voiture.'], 400);
        }

        // Voter ID (cookie)
        $voterId = $this->getOrCreateVoterId($request);

        if ($voters->findOne(['voterId' => $voterId])) {
            $resp = $this->json(['ok' => false, 'message' => 'Vous avez déjà voté.'], 409);
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

    /* --------------------------------- Utils ---------------------------------- */

    /** Classement simple : total + items triés par nb de votes décroissant */
    private function getRanking(Collection $votesColl): array
    {
        $counts = [];
        foreach ($votesColl->find([]) as $doc) {
            $car = (string)($doc['car'] ?? '');
            $counts[$car] = (int)($doc['votes'] ?? 0);
        }

        $items = [];
        $total = 0;
        foreach (self::CARS as $car) {
            $v = $counts[$car] ?? 0;
            $items[] = ['label' => $car, 'votes' => $v];
            $total  += $v;
        }

        usort($items, fn ($a, $b) => $b['votes'] <=> $a['votes']);

        return ['total' => $total, 'items' => $items];
    }

    /** Récupère le voter_id du cookie ou en génère un */
    private function getOrCreateVoterId(Request $request): string
    {
        $id = (string) $request->cookies->get(self::COOKIE_NAME, '');
        return $id !== '' ? $id : bin2hex(random_bytes(16));
    }

    /** Pose le cookie s’il n’existe pas encore */
    private function ensureVoterCookie(Request $request, Response $response): void
    {
        if ($request->cookies->has(self::COOKIE_NAME)) {
            return;
        }
        $id = $this->getOrCreateVoterId($request);
        $cookie = Cookie::create(self::COOKIE_NAME, $id, new \DateTime('+1 year'))
            ->withHttpOnly()
            ->withSecure($request->isSecure()) // true si HTTPS (Heroku)
            ->withPath('/')
            ->withSameSite('Lax');

        $response->headers->setCookie($cookie);
    }
}
