<?php

namespace App\Mapper;

use App\ApiResource\MenuApi;
use App\Entity\Menu;
use Symfony\Bundle\SecurityBundle\Security;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;
use App\ApiResource\UserApi;


#[AsMapper(from: Menu::class, to: MenuApi::class)]
class MenuEntityToApiMapper implements MapperInterface
{
    public function __construct(
//        private MicroMapperInterface $microMapper,
        private Security $security,
    ) {}

    public function load(object $from, string $toClass, array $context): object
    {
        $entity = $from;
        assert($entity instanceof Menu);

        $dto = new MenuApi();
        $dto->id = $entity->getId();

        return $dto;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $entity = $from;
        $dto = $to;
        assert($entity instanceof Menu);
        assert($dto instanceof MenuApi);

        $dto->productName = $entity->getProductName();
        $dto->description = $entity->getDescription();
        $dto->price = $entity->getPrice();
        $dto->isOwnedByCurrentUser = $this->security->getUser() && $this->security->getUser() ;

        return $dto;
    }
}
