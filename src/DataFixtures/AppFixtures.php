<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $userPasswordEncoder;
    /**
     * AppFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder =$userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for($i=0;$i<20;$i++){
            $user=new User();
            $user->setFirstName("hama$i")
                 ->setLastName("brah$i")
                 ->setEmail("user$i@gmail.com")
                 ->setPassword($this->userPasswordEncoder->encodePassword($user,"zied"))
                 ->setIntroduction("Jawek behi $i")
                 ->setSlug("hama-$i");
            $manager->persist($user);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
