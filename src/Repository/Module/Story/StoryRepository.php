<?php

namespace App\Repository\Module\Story;

use App\Entity\Core\Website;
use App\Entity\Module\Story\Story;
use App\Service\Core\CacheService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Exception\CacheException;

/**
 * StoryRepository.
 *
 * @extends ServiceEntityRepository<Story>
 *
 * @method Story|null find($id, $lockMode = null, $lockVersion = null)
 * @method Story|null findOneBy(array $criteria, array $orderBy = null)
 * @method Story[]    findAll()
 * @method Story[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @author Sébastien FOURNIER <contact@sebastien-fournier.com>
 */
class StoryRepository extends ServiceEntityRepository
{
    /**
     * StoryRepository constructor.
     */
    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly CacheService $cacheService)
    {
        parent::__construct($this->registry, Story::class);
    }

    /**
     * Find one by filter.
     *
     * @throws CacheException
     * @throws NonUniqueResultException
     */
    public function findActivated(Website $website, string $locale, int $limit = 0): array
    {
        $cache = $this->cacheService->adapter(Story::class, __FUNCTION__);

        $queryBuilder = $this->createQueryBuilder('s')
            ->andWhere('s.website = :website')
            ->andWhere('s.active = :active')
            ->andWhere('s.locale = :locale')
            ->setParameter('website', $website)
            ->setParameter('active', true)
            ->setParameter('locale', $locale)
            ->orderBy('s.updatedAt', 'DESC')
            ->getQuery();

        if ($limit > 1) {
            $queryBuilder->setMaxResults($limit);
        }

        if ($cache instanceof PhpFilesAdapter) {
            $queryBuilder->setResultCache($cache)->enableResultCache();
        }

        return 1 === $limit ? $queryBuilder->getOneOrNullResult() : $queryBuilder->getResult();
    }
}
