<?php
/**
 * Element fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Element;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ElementFixtures.
 */
class ElementFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'elements', function (int $i) {
            $element = new Element();
            $element->setTitle($this->faker->sentence);
            $element->setSlug($this->faker->word);
            $element->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $element->setUpdatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $element->setContent($this->faker->paragraphs(
                $this->faker->numberBetween(1, 4),
                true
            ));

            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $element->setCategory($category);

            return $element;
        });
        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
