<?php

namespace bookin\Composer;

use bookin\Composer\Console\Application\WebApplication;
use bookin\Composer\Console\Formatter\BootstrapOutputFormatter;
use bookin\Composer\Console\Output\ComposerOutput;
use bookin\Composer\Repository\CompositeRepository;
use Composer\Factory;
use Composer\IO\NullIO;
use Composer\Repository\PlatformRepository;
use Composer\Repository\RepositoryInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 *
 */
class Composer {

  public static $configFile = NULL;

  public static $configFilePath = NULL;

  private static $composer;

  private static $app;

  private static $_instance;

  /**
   *
   */
  private function __construct() {
  }

  /**
   *
   */
  private function __clone() {
  }

  /**
   * @param null $configFile
   * @param null $configFilePath
   *
   * @return Composer
   */
  public static function getInstance($configFile = NULL, $configFilePath = NULL) {
    putenv('COMPOSER=' . $configFile);
    self::$configFile = $configFile;
    self::$configFilePath = $configFilePath;

    if (NULL === self::$_instance) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * @return \Composer\Composer
   */
  public static function getComposer() {
    if (!self::$composer) {
      $factory = new Factory();
      self::$composer = $factory->createComposer(new NullIO(), self::$configFile, FALSE, self::$configFilePath);
    }
    return self::$composer;
  }

  /**
   * @return \Composer\Package\PackageInterface[]
   */
  public static function getLocalPackages() {
    return self::getComposer()
      ->getRepositoryManager()
      ->getLocalRepository()
      ->getCanonicalPackages();
  }

  /**
   * @param $name
   * @param array $options
   *
   * @return string
   */
  public static function updatePackage($name, $options = []) {
    return self::runCommand('update', [$name] + $options);
  }

  /**
   * @param array $options
   *
   * @return string
   */
  public static function updateAllPackages($options = []) {
    return self::runCommand('update', $options);
  }

  /**
   * @param array $options
   *
   * @return string
   */
  public static function deleteAllPackages($options = []) {
    return self::runCommand('remove', $options);
  }

  /**
   * @param $name
   * @param array $options
   *
   * @return string
   */
  public static function deletePackage($name, $options = []) {
    return self::runCommand('remove', [$name] + $options);
  }

  /**
   * @param $search
   *
   * @return array|mixed
   * @throws \Composer\Json\JsonValidationException
   */
  public static function searchPackage($search) {
    /** @var Application $app */
    $app = self::getApplication();
    $composer = $app->getComposer(TRUE, FALSE);
    $platformRepo = new PlatformRepository();
    $localRepo = $composer->getRepositoryManager()->getLocalRepository();
    $installedRepo = new CompositeRepository([$localRepo, $platformRepo]);
    $repos = new CompositeRepository(array_merge([$installedRepo], $composer->getRepositoryManager()
      ->getRepositories()));
    $flags = RepositoryInterface::SEARCH_FULLTEXT;
    $results = $repos->search($search, $flags);

    return $results;
  }

  /**
   * @param $name
   * @param null $version
   *
   * @return \Composer\Package\PackageInterface|null
   * @throws \Exception
   */
  public static function findPackage($name, $version = NULL) {
    if (strpos($name, '/') === FALSE) {
      throw new \Exception('You need use full package name: vendor/vendor1');
    }
    /** @var \Composer\Repository\RepositoryManager $repositoriManager */
    $repositoryManager = self::getComposer()->getRepositoryManager();
    $package = $repositoryManager->findPackage($name, $version);
    return $package;
  }

  /**
   * @return \bookin\Composer\Console\Application\WebApplication
   */
  public static function getApplication() {
    if (empty(self::$app)) {
      $app = new WebApplication();
      $app->setComposer(self::getComposer());
      $app->setAutoExit(FALSE);
      self::$app = $app;
    }
    return self::$app;
  }

  /**
   * @param string $command
   * @param array $params
   *
   * @return string
   */
  public static function runCommand($command = '', $params = []) {

    if (empty($command)) {
      $command = 'list';
    }

    $parameters = ['command' => $command] + $params;

    $input = new ArrayInput($parameters);
    $output = new ComposerOutput();

    $output->setFormatter(new BootstrapOutputFormatter());

    try {
      $app = self::getApplication();
      $app->run($input, $output);
    }
    catch (\Exception $c) {
      $output->write($c->getMessage());
    }

    return $output->getMessage();
  }

}
