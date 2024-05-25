<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SuperAdminFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $superAdmin =$this->createSuperAdmin();
        $manager->persist($superAdmin);
        $manager->flush();
    }

    private function createSuperAdmin(): User
    {
        $superAdmin = new User;

        $superAdmin->setPrenom("Marion");
        $superAdmin->setNom("Dellupo");
        $superAdmin->setEmail("canap-plus@gmail.com");
        $superAdmin->setRoles(["ROLE_SUPER_ADMIN", "ROLE_ADMIN", "ROLE_USER"]);
        $superAdmin->setVerified(true);
        $superAdmin->setTelephone("0141414141");
        $superAdmin->setVerifiedAt( new DateTimeImmutable());
        $superAdmin->setAdresse("1 rue de Paris");
        $superAdmin->setVille("Paris");
        $superAdmin->setCp(75008);

        $passwordSuperAdmin = $this->hasher->hashPassword($superAdmin, "2Y6zGjqp?!");
        $superAdmin->setPassword($passwordSuperAdmin);
        
        return $superAdmin;
    }
}
