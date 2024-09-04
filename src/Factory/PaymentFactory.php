<?php

namespace App\Factory;

use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Payment>
 *
 * @method        Payment|Proxy create(array|callable $attributes = [])
 * @method static Payment|Proxy createOne(array $attributes = [])
 * @method static Payment|Proxy find(object|array|mixed $criteria)
 * @method static Payment|Proxy findOrCreate(array $attributes)
 * @method static Payment|Proxy first(string $sortedField = 'id')
 * @method static Payment|Proxy last(string $sortedField = 'id')
 * @method static Payment|Proxy random(array $attributes = [])
 * @method static Payment|Proxy randomOrCreate(array $attributes = [])
 * @method static PaymentRepository|RepositoryProxy repository()
 * @method static Payment[]|Proxy[] all()
 * @method static Payment[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Payment[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Payment[]|Proxy[] findBy(array $attributes)
 * @method static Payment[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Payment[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class PaymentFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'amount' => self::faker()->randomFloat(),
            'method' => self::faker()->text(50),
            'order' => OrderFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Payment $payment): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Payment::class;
    }
}
