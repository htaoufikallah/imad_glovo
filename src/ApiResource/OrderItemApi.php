<?php


namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\OrderItem;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'OrderItem',
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            security: 'is_granted("ROLE_CLIENT")',
        ),
        new Patch(
            security: 'is_granted("ROLE_CLIENT")',
        ),
        new Delete(
            security: 'is_granted("ROLE_CLIENT")',
        )
    ],
    paginationItemsPerPage: 10
)]
class OrderItemApi
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;

    #[Assert\NotBlank]
    public int $quantity = 0;

    #[Assert\GreaterThanOrEqual(0)]
    public float $totalPrice = 0.0;

    // Relationships
    public ?OrderApi $order = null;
}
