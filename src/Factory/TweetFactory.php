<?php

namespace App\Factory;

use App\Entity\Tweet;
use App\Repository\TweetRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Tweet|Proxy createOne(array $attributes = [])
 * @method static Tweet[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Tweet|Proxy findOrCreate(array $attributes)
 * @method static Tweet|Proxy random(array $attributes = [])
 * @method static Tweet|Proxy randomOrCreate(array $attributes = [])
 * @method static Tweet[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Tweet[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TweetRepository|RepositoryProxy repository()
 * @method Tweet|Proxy create($attributes = [])
 */
final class TweetFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'content' => $this->faker()->paragraph(),
            'createdAt' => $this->faker()->dateTimeBetween('-6 months'),
            'deletedAt' => $this->faker()->boolean(95) ? null : $this->faker()->dateTimeBetween('-6 months')
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            ->afterInstantiate(function (Tweet $tweet) {
                if ($tweet->getAuthor() === null) {
                    $tweet->setAuthor(UserFactory::createOne());
                }
            });
    }

    protected static function getClass(): string
    {
        return Tweet::class;
    }
}
