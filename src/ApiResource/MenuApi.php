<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Menu;
use App\State\EntityToDtoStateProvider;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    shortName: 'Menu',
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            security: 'is_granted("ROLE_RESTAURANT_OWNER_MENU_CREATE")',
        ),
        new Patch(
            security: 'is_granted("ROLE_RESTAURANT_OWNER_MENU_EDIT")',
        ),
        new Delete(
            security: 'is_granted("ROLE_RESTAURANT_OWNER_MENU_DELETE")',
        )
    ],
    paginationItemsPerPage: 10,
    provider: EntityToDtoStateProvider::class,
    stateOptions: new Options(entityClass: Menu::class),

)]
class MenuApi
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;

    #[NotBlank]
    public ?string $productName = null;

    public ?string $description = null;

    #[GreaterThanOrEqual(0)]
    public ?float $price = null;

    #[ApiProperty(readable: false, writable: false)]
    public ?UserApi $owner = null;

    // Additional computed properties
    public ?string $formattedPrice = null;

    public ?bool $isAvailable = null;
}
