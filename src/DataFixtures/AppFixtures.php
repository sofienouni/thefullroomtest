<?php

namespace App\DataFixtures;

use App\Entity\Conges;
use App\Entity\StatusConges;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
//        à utiliser pour remplir avec faker

        $faker = Faker\Factory::create('fr_FR');
        $status = $manager->getRepository(StatusConges::class)->findAll();
        $listes_status[] = 'en attente';
        $listes_status[] = 'approuvé';
        $listes_status[] = 'refusé';

        if (empty($status)){
            foreach ($listes_status as $liste_status){
                $status_conges = new StatusConges();
                $status_conges->setStatus($liste_status);
                $manager->persist($status_conges);
            }
            $manager->flush();
            $status = $manager->getRepository(StatusConges::class)->findAll();
        }
        $users = $manager->getRepository(User::class)->findAll();

        for ($i= 0; $i<10; $i++){
            $conges = new Conges();
            $start_date = $faker->dateTimeBetween('+1 day', '+1 year');
            $end_date = clone $start_date;
            $days_number = $faker->biasedNumberBetween('1', '10');
            $end_date->modify("+$days_number day");
            $conges->setStartDate($start_date);
            $conges->setEndDate($end_date);
            $conges->setStatus($faker->randomElement($status));
            $conges->setUser($faker->randomElement($users));
            $manager->persist($conges);
        }

        $manager->flush();
    }
}
