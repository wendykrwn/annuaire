<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Group;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PostFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_FR");

        // Créer 3 group fakées
        for($i = 1; $i <= 3; $i++) {
            $group_name = new Group();

            $group_name->setName($faker->word)
                       ->setPromo($faker->word);

            $manager->persist($group_name);

            //Créer entre 4 et 6 users
            for($j=1; $j <= mt_rand(4,6); $j++){
                $user = new User();
                
                $user->setEmail($faker->email)
                     ->setFirstName($faker->firstName())
                     ->setLastName($faker->lastName)
                     ->setAddress($faker->address)
                     ->setCity($faker->city)
                     ->setAlternanceJob($faker->jobTitle)
                    //  ->setImage($faker->imageUrl(640, 480))
                     ->setImage('https://images.unsplash.com/photo-1546069901-d5bfd2cbfb1f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=880&q=80')
                     ->setBirthDate($faker->dateTimeBetween('-30 years', '-15 years'))
                     ->setPassword($this->encoder->encodePassword($user, $faker->password(8)))
                     ->setGroupName($group_name)
                ;

                $manager->persist($user);

                // Créer entre 0 et 10 posts par utilisateur
                for($k = 1; $k <= mt_rand(0,10); $k++){
                    $post = new Post();
                    $post->setContent($faker->paragraph())
                         ->setImage('https://images.unsplash.com/photo-1548247416-ec66f4900b2e?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=720&q=80')
                         ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                         ->setUser($user)
                         ;
                    
                    $manager->persist($post);
                }
            }
        }
        $manager->flush();
    }
}
