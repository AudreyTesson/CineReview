<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use DateTime;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class Movie1430Voter extends Voter
{
    /**
     *
     * @param string $attribute 
     * @param $subject 
     * @return boolean
     */
    protected function supports(string $attribute, $subject): bool
    {
        if ($attribute === "MOVIE_2030" && ($subject instanceof Movie || $subject === null)){
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $attribute
     * @param $subject
     * @param TokenInterface $token
     * @return boolean
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {return false;}

        $dateDuJour = new DateTime("now");
        $heure = $dateDuJour->format("Gi");
        if ($heure > 2030){
            return false;
        }

        return true;
    }
}
