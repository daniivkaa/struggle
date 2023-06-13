<?php

namespace App\Controller\Api\Mobile;

use App\Entity\Competition;
use App\Entity\Friend;
use App\Entity\Notice;
use App\Entity\Player;
use App\Repository\UserRepository;
use App\Service\CompetitionService;
use App\Service\GameService;
use App\Service\PlayerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MobileController extends AbstractController
{
    private $em;

    private $passwordEncoder;

    private $userRepository;

    private $playerService;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository, PlayerService $playerService)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->playerService = $playerService;
    }

    /**
     * @Route("/api/mobile/login", name="api_mobile_login")
     */
    public function mobileLogin(Request $request): Response
    {
        $data = ['success' => false];

        if ($this->isLogin($request)) {
            $data['success'] = true;
            return new JsonResponse ($data);
        }

        return new JsonResponse ($data);
    }

    /**
     * @Route("/api/mobile/competition", name="api_mobile_competition")
     */
    public function mobileCompetition(Request $request): Response
    {
        $data = ['success' => false];

        if ($this->isLogin($request)) {
            $data['success'] = true;

            $competitions = $this->em->getRepository(Competition::class)->findBy(['public' => true, "isActive" => true], ['id' => 'DESC']);
            foreach ($competitions as $competition) {
                $data['competition'][] = [
                    'id' => $competition->getId(),
                    'name' => $competition->getName(),
                    'description' => $competition->getDescription(),
                ];
            }

            return new JsonResponse ($data);
        }

        return new JsonResponse ($data);
    }

    /**
     * @Route("/api/mobile/competition/join", name="api_mobile_competition_join")
     */
    public function mobileCompetitionJoin(Request $request): Response
    {
        $competitionId = $request->query->get('competition');
        $data = ['success' => false];

        if ($user = $this->isLogin($request)) {
            $competition = $this->em->getRepository(Competition::class)->findOneBy(['id' => $competitionId]);

            if (!$competition) {
                return new JsonResponse ($data);
            }

            $isPlayer = $this->em->getRepository(Player::class)->findOneBy(["competition" => $competition, "users" => $user]);

            if ($competition->getPublic() && !$isPlayer) {
                $data['success'] = true;
                $this->playerService->addPlayer($competition, $user);

                return new JsonResponse ($data);
            }
        }

        return new JsonResponse ($data);
    }

    /**
     * @Route("/api/mobile/competition/games", name="api_mobile_competition_games")
     */
    public function mobileCompetitionGames(Request $request): Response
    {
        $competitionId = $request->query->get('competition');
        $data = ['success' => false];

        if ($user = $this->isLogin($request)) {
            $competition = $this->em->getRepository(Competition::class)->findOneBy(['id' => $competitionId]);

            if (!$competition) {
                return new JsonResponse ($data);
            }

            $player = $this->em->getRepository(Player::class)->findOneBy(["users" => $user, "competition" => $competition]);

            if ($player) {
                $data['success'] = true;

                foreach ($competition->getGames() as $game) {
                    if (false === $game->getIsActive()) {
                        continue;
                    }
                    $data['game'][] = [
                        'firstPlayer' => $game->getFirstPlayer()->getFirstName(),
                        'secondPlayer' => $game->getSecondPlayer()->getFirstName(),
                    ];
                }

                return new JsonResponse ($data);
            }
        }

        return new JsonResponse ($data);
    }

    /**
     * @Route("/api/mobile/notice/show", name="api_mobile_notice_show")
     */
    public function mobileNoticeShow(Request $request): Response
    {
        $data = ['success' => false];

        if ($user = $this->isLogin($request)) {
            $notices = $this->em->getRepository(Notice::class)->findBy(["targetUser" => $user->getId(), 'active' => true]);

            if ($notices) {
                $data['success'] = true;

                foreach ($notices as $notice) {
                    $data['notice'][] = [
                        'id' => $notice->getId(),
                        'targetUser' => $notice->getTargetUser()->getId(),
                        'type' => $notice->getType(),
                        'secondUser' => $notice->getSecondUser() ? $notice->getSecondUser()->getId() : null,
                        'competition' => $notice->getCompetition() ? $notice->getCompetition()->getId() : null,
                    ];
                }

                return new JsonResponse ($data);
            }

        }

        return new JsonResponse ($data);
    }

    /**
     * @Route("/api/mobile/notice/answer", name="api_mobile_notice_answer")
     */
    public function mobileNoticeAnswer(Request $request): Response
    {
        $competitionId = $request->query->get('competition');
        $secondUserId = $request->query->get('secondUser');
        $data = ['success' => false];

        if ($user = $this->isLogin($request)) {
            if ($competitionId) {
                $competition = $this->em->getRepository(Competition::class)->findOneBy(['id' => $competitionId]);

                if (!$competition) {
                    return new JsonResponse ($data);
                }

                $notice = $this->em->getRepository(Notice::class)->findOneBy(["type" => "invite_competition", "targetUser" => $user, "competition" => $competition, "active" => true]);

                if($notice){
                    $notice->setActive(false);
                    $this->em->persist($notice);
                    $this->em->flush();

                    $player = $this->playerService->addPlayer($competition, $user);

                    $data['success'] = true;
                    return new JsonResponse ($data);
                }
            } else if ($secondUserId) {
                $secondUser = $this->userRepository->findOneBy(['id' => $secondUserId]);

                if (!$secondUser) {
                    return new JsonResponse ($data);
                }

                $friend = $this->em->getRepository(Friend::class)->findOneBy(["targetUser" => $user, "secondUser" => $secondUser]);
                $notice = $this->em->getRepository(Notice::class)->findOneBy(["targetUser" => $user, "secondUser" => $secondUser, 'active' => true]);

                if ($friend || !$notice) {
                    return new JsonResponse ($data);
                }

                $notice->setActive(false);
                $this->em->persist($notice);

                $targetFriend = new Friend();
                $secondFriend = new Friend();

                $targetFriend->setTargetUser($user);
                $targetFriend->setSecondUser($secondUser);
                $this->em->persist($targetFriend);

                $secondFriend->setTargetUser($secondUser);
                $secondFriend->setSecondUser($user);
                $this->em->persist($secondFriend);

                $this->em->flush();
                $data['success'] = true;

                return new JsonResponse ($data);
            }
        }

        return new JsonResponse ($data);
    }

    private function isLogin (Request $request)
    {
        $login = $request->query->get('login');
        $password = $request->query->get('password');

        $user = $this->userRepository->findOneBy(['email' => $login]);

        if (!$user) {
            return false;
        }

        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            return $user;
        }

        return false;
    }
}
