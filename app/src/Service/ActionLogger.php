<?php
namespace App\Service;

use App\Entity\UserActions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\User\UserInterface;

class ActionLogger 
{
    public function __construct(
        private EntityManagerInterface $em,
        private requestStack $requestStack,
    ) {}
    public function log(UserInterface $user , string $action) : void 
    {
        $request = $this->requestStack->getCurrentrequest();

        $entry = new UserActions();
        $entry->setuserId($user);
        $entry->setAction($action);
        $entry->setIp($request?->getClientIp());
        $entry->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($entry);
        $this->em->flush();
    }
    
}