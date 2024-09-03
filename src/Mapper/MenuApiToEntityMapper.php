<?php

namespace App\Mapper;


use App\ApiResource\MenuApi;
use App\Entity\Menu;
use App\Entity\User;
use App\Repository\MenuRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMapper(from: MenuApi::class, to: Menu::class)]
class MenuApiToEntityMapper implements MapperInterface
{
    public function __construct(
        private MenuRepository $repository,
        private Security $security,
        private MicroMapperInterface $microMapper,
    )
    {

    }

    public function load(object $from, string $toClass, array $context): object
    {
        $dto = $from;
        assert($dto instanceof MenuApi);

        $entity = $dto->id ? $this->repository->find($dto->id) : new Menu($dto->productName);
        if (!$entity) {
            throw new \Exception('Menu not found');
        }

        return $entity;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        $entity = $to;
        assert($dto instanceof MenuApi);
        assert($entity instanceof Menu);

        if ($dto->owner) {
            $entity->setOwner($this->microMapper->map($dto->owner, User::class, [
                MicroMapperInterface::MAX_DEPTH => 0,
            ]));
        } else {
            $entity->setOwner($this->security->getUser());
        }

        $entity->setProductName($dto->productName);
        $entity->setDescription($dto->description);
        $entity->setPrice($dto->price);


        return $entity;
    }
}
