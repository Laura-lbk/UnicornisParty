<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\ArticleNews;

class ArticleNewsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=1; $i <= 10; $i++){
            $article = new ArticleNews();
            $article->setTitre("Titre de l'article n°$i")
                    ->setContenu("<p>Contenu de l'article n°$i<p>")
                    ->setImage("http://placehold.it/350x150")
                    ->setDateCreation(new \DateTime());
                    
                    
 
             $manager->persist($article);
        }

        $manager->flush();
    }
}
