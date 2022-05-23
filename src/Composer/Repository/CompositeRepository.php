<?php

namespace bookin\Composer\Repository;

use Composer\Repository\CompositeRepository as BaseCompositeRepository;

/**
 *
 */
class CompositeRepository extends BaseCompositeRepository {

  /**
   * {@inheritdoc}
   */
  public function search($query, $mode = 0) {
    $matches = [];
    foreach ($this->getRepositories() as $repository) {
      /** @var RepositoryInterface $repository */
      $matches[] = $repository->search($query, $mode);
    }

    $matches = $matches ? call_user_func_array('array_merge', $matches) : [];

    return $matches;
  }

}
