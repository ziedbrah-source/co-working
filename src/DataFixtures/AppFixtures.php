<?php

namespace App\DataFixtures;

use App\Entity\Role;
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
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        $adminUser=new User();
        $adminUser->setFirstName("Zied")
                ->setLastName("BRAH")
                ->setEmail("admin@gmail.com")
                ->setIsVerified(true)
                ->setPassword($this->userPasswordEncoder->encodePassword($adminUser,"zied"))
                ->addUserRole($adminRole);
        $manager->persist($adminUser);for($i=0;$i<20;$i++){
            $user=new User();
            $user->setFirstName("hama$i")
                 ->setLastName("brah$i")
                 ->setEmail("user$i@gmail.com")
                 ->setPassword($this->userPasswordEncoder->encodePassword($user,"zied"))
                 ->setIntroduction("Jawek behi $i")
                 ->setSlug("hama-$i")
                 ->setIsVerified(true);
            $manager->persist($user);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
