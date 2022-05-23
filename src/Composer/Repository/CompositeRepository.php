<?php

namespace bookin\Composer\Repository;

use Composer\Repository\CompositeRepository as BaseCompositeRepository;
use Composer\Repository\RepositoryInterface;

class CompositeRepository extends BaseCompositeRepository
{
    /**
     * {@inheritdoc}
     */
    public function search($query, $mode = 0)
    {
        $matches = array();
        foreach ($this->getRepositories() as $repository) {
            /* @var $repository RepositoryInterface */
            $matches[] = $repository->search($query, $mode);
        }

        $matches = $matches ? call_user_func_array('array_merge', $matches) : array();

        return $matches;
    }
}
