<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
	/** @var UserPasswordEncoderInterface */
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

    public function load(ObjectManager $manager): void
    {
    	$user = new User();
    	$user->setEmail('admin@admin.com');
    	$user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        
        $manager->persist($user);
        $manager->flush();
    }
}
