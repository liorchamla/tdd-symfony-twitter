<?php

namespace App\Factory;

use App\Entity\Follow;
use App\Repository\FollowRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Follow|Proxy createOne(array $attributes = [])
 * @method static Follow[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Follow|Proxy findOrCreate(array $attributes)
 * @method static Follow|Proxy random(array $attributes = [])
 * @method static Follow|Proxy randomOrCreate(array $attributes = [])
 * @method static Follow[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Follow[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static FollowRepository|RepositoryProxy repository()
 * @method Follow|Proxy create($attributes = [])
 */
final class FollowFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'follower' => UserFactory::createOne(),
            'followed' => UserFactory::createOne(),
            'createdAt' => $this->faker()->dateTimeBetween('-6 months')
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Follow $follow) {})
        ;
    }

    protected static function getClass(): string
    {
        return Follow::class;
    }
}
