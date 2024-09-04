<?php


namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Order;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Order',
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
class OrderApi
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;

    #[Assert\NotBlank]
    public ?string $clientName = null;

    #[Assert\NotNull]
    public ?\DateTimeImmutable $orderDate = null;

    #[Assert\NotBlank]
    public ?string $status = null;

    // Relationships
    public ?OrderItemApi $orderItems = null;
    public ?PaymentApi $payment = null;
}
