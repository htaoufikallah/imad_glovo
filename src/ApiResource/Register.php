<?php


namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\RegisterAction;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            uriTemplate: '/register',
            controller: RegisterAction::class
        )
    ],
    denormalizationContext: ['groups' => ['register:write']])
]
final class Register
{
    #[Groups('register:write')]
    public string $email;

    #[Groups('register:write')]
    #[Assert\NotBlank]
    #[Assert\NotCompromisedPassword]
    public ?string $password;

    #[Groups('register:write')]
    #[Assert\IdenticalTo(propertyPath: 'password')]
    public ?string $rePassword;
}
