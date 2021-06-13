<?php
namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
public function checkPreAuth(UserInterface $user): void
{

if (!$user instanceof AppUser) {
return;
}


if (!($user->isVerified())) {
// the message passed to this exception is meant to be displayed to the user
throw new CustomUserMessageAccountStatusException('Your user account is not verified, please Verify it using the link that you received in your email');
}
}

public function checkPostAuth(UserInterface $user): void
{
if (!$user instanceof AppUser) {
return;
}

}
}