<?php

namespace App\Controller;

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Driver\Exception\BulkWriteException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
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

    public function __construct(
        private Client $mongo,
        #[Autowire('%env(MONGODB_DB)%')] private string $dbName,
        #[Autowire('%env(MONGODB_COLLECTION_VOTES)%')] private string $votesCollection,
        #[Autowire('%env(MONGODB_COLLECTION_VOTERS)%')] private string $votersCollection,
    ) {}

    /* ------------------------------ Helpers DB ------------------------------ */

    private function db(): Database
    {
        return $this->mongo->selectDatabase($this->dbName);
    }

    private function votesColl(): Collection
    {
        return $this->db()->selectCollection($this->votesCollection);
    }

    private function votersColl(): Collection
    {
        return $this->db()->selectCollection($this->votersCollection);
    }

    /* -------------------------------- Routes -------------------------------- */

    #[Route('/', name: 'app_home', methods: ['GET','POST'])]
    public function index(Request $request): Response
    {
        if (!$request->isMethod('POST')) {
            $resp = $this->render('home/index.html.twig');
            $this->ensureVoterCookie($request, $resp);
            return $resp;
        }

        // Fallback POST si JS off
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('vote', $token)) {
            $this->addFlash('error', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('app_home', ['_fragment' => 'vote']);
        }

        $selected = (string)$request->request->get('vote', '');
        if ($selected === '') {
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
            'ua'      => substr((string)$request->headers->get('User-Agent'), 0, 255),
        ]);

        $this->addFlash('success', 'Merci pour votre participation !');

        $resp = $this->redirectToRoute('app_home', ['_fragment' => 'vote']);
        $this->ensureVoterCookie($request, $resp);
        return $resp;
    }

    #[Route('/vote/submit', name: 'app_vote_ajax', methods: ['POST'])]
    public function voteAjax(Request $request): JsonResponse
    {
        try {
            $votesColl = $this->votesColl();
            $voters    = $this->votersColl();
            $voters->createIndex(['voterId' => 1], ['unique' => true]);

            // CSRF
            $token = $request->request->get('_token');
            if (!$this->isCsrfTokenValid('vote', $token)) {
                return $this->json(['ok' => false, 'message' => 'Jeton CSRF invalide.'], 400);
            }

            // Choix
            $selected = (string)$request->request->get('vote', '');
            if ($selected === '') {
                return $this->json(['ok' => false, 'message' => 'Veuillez sélectionner une voiture.'], 400);
            }

            // Voter ID via cookie
            $voterId = $this->getOrCreateVoterId($request);

            if ($voters->findOne(['voterId' => $voterId])) {
                $resp = $this->json(['ok' => false, 'message' => 'Vous avez déjà voté.'], 409);
                $this->ensureVoterCookie($request, $resp);
                return $resp;
            }

            // Enregistre vote + marquage votant
            $votesColl->updateOne(['car' => $selected], ['$inc' => ['votes' => 1]], ['upsert' => true]);

            try {
                $voters->insertOne([
                    'voterId' => $voterId,
                    'choice'  => $selected,
                    'at'      => new UTCDateTime(),
                    'ip'      => $request->getClientIp(),
                    'ua'      => substr((string)$request->headers->get('User-Agent'), 0, 255),
                ]);
            } catch (BulkWriteException $e) {
                // Double-clic → violation d'unicité
                if ($e->getCode() === 11000) {
                    $resp = $this->json(['ok' => false, 'message' => 'Vous avez déjà voté.'], 409);
                    $this->ensureVoterCookie($request, $resp);
                    return $resp;
                }
                throw $e;
            }

            $results = $this->getRanking($votesColl);

            $resp = $this->json([
                'ok'      => true,
                'message' => 'Merci pour votre participation !',
                'total'   => $results['total'],
                'items'   => $results['items'],
            ]);
            $this->ensureVoterCookie($request, $resp);
            return $resp;

        } catch (\Throwable $e) {
            // En prod, tu peux retirer "detail" si besoin
            return $this->json(['ok' => false, 'message' => 'Erreur serveur', 'detail' => $e->getMessage()], 500);
        }
    }

    /* --------------------------------- Utils --------------------------------- */

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

    private function getOrCreateVoterId(Request $request): string
    {
        $id = (string)$request->cookies->get(self::COOKIE_NAME, '');
        return $id !== '' ? $id : bin2hex(random_bytes(16));
    }

    private function ensureVoterCookie(Request $request, Response $response): void
    {
        if ($request->cookies->has(self::COOKIE_NAME)) {
            return;
        }
        $id = $this->getOrCreateVoterId($request);
        $cookie = Cookie::create(self::COOKIE_NAME, $id, new \DateTime('+1 year'))
            ->withHttpOnly()
            ->withSecure($request->isSecure())
            ->withPath('/')
            ->withSameSite('Lax');

        $response->headers->setCookie($cookie);
    }
}
