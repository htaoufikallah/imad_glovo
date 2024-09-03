<?php
//
//namespace App\DataFixtures;
//
//use App\Factory\ApiTokenFactory;
//use App\Factory\DragonTreasureFactory;
//use App\Factory\UserFactory;
//use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Persistence\ObjectManager;
//
//class AppFixtures extends Fixture
//{
//    public function load(ObjectManager $manager): void
//    {
//        UserFactory::createOne([
//            'email' => 'bernie@dragonmail.com',
//            'password' => 'roar',
//        ]);
//
//        UserFactory::createMany(10);
//        DragonTreasureFactory::createMany(40, function () {
//            return [
//                'owner' => UserFactory::random(),
//                'isPublished' => rand(0, 10) > 3,
//            ];
//        });
//
//        ApiTokenFactory::createMany(30, function () {
//            return [
//                'ownedBy' => UserFactory::random(),
//            ];
//        });
//    }
//}




namespace App\DataFixtures;

use App\Factory\ApiTokenFactory;
use App\Factory\DragonTreasureFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créez un utilisateur Restaurant Owner avec un mot de passe haché
        $restaurantOwner = UserFactory::new()->createOne([
            'email' => 'restaurant_owner@example.com',
            'roles' => ['ROLE_RESTAURANT_OWNER', 'ROLE_RESTAURANT_OWNER_MENU_CREATE', 'ROLE_RESTAURANT_OWNER_MENU_EDIT', 'ROLE_RESTAURANT_OWNER_MENU_DELETE']
        ])->object();

        $restaurantOwner->setPassword(
            $this->passwordHasher->hashPassword($restaurantOwner, 'password')
        );

        $manager->persist($restaurantOwner);

        // Créez un utilisateur Glovo Manager avec un mot de passe haché
        $glovoManager = UserFactory::new()->createOne([
            'email' => 'glovo_manager@example.com',
            'roles' => ['ROLE_GLOVO_MANAGER']
        ])->object();

        $glovoManager->setPassword(
            $this->passwordHasher->hashPassword($glovoManager, 'password')
        );

        $manager->persist($glovoManager);

        // Créez un utilisateur Client avec un mot de passe haché
        $client = UserFactory::new()->createOne([
            'email' => 'client@example.com',
            'roles' => ['ROLE_CLIENT']
        ])->object();

        $client->setPassword(
            $this->passwordHasher->hashPassword($client, 'password')
        );

        $manager->persist($client);

        // Créez d'autres utilisateurs en utilisant UserFactory
        UserFactory::new()->createMany(10, function() {
            return [
                'password' => $this->passwordHasher->hashPassword(
                    UserFactory::new()->createOne()->object(),
                    'password'
                ),
                'roles' => ['ROLE_CLIENT'], // Exemple de rôle par défaut
            ];
        });

        // Créez des trésors avec DragonTreasureFactory
        DragonTreasureFactory::new()->createMany(40, function () {
            return [
                'owner' => UserFactory::new()->random(),
                'isPublished' => rand(0, 10) > 3,
            ];
        });

        // Créez des jetons API avec ApiTokenFactory
        ApiTokenFactory::new()->createMany(30, function () {
            return [
                'ownedBy' => UserFactory::new()->random(),
            ];
        });

        $manager->flush();
    }
}
