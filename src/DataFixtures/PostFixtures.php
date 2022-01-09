<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10; $i++){
            $post = new Post();
            $post->setName("Nom du post n°$i")
                 ->setContent("Contenu du post n°$i")
                 ->setImage("https://media.istockphoto.com/photos/circuit-blue-board-background-copy-space-computer-data-technology-picture-id1340728386?k=20&m=1340728386&s=612x612&w=0&h=rndn_XMSEUAudwxv5dXQXC2-Vu6262xZFeY7PBV0XVc=")
                 ->setCreatedAt(new \DateTime());
            
            $manager->persist($post);
        }

        $manager->flush();
    }
}
