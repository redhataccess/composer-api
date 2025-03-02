<?php

namespace bookin\Composer\Console\Application;

use bookin\Composer\Composer;
use Composer\Console\Application;
use Composer\Json\JsonValidationException;
use Composer\Factory;

/**
 *
 */
class WebApplication extends Application {

  /**
   * @param $composer
   */
  public function setComposer($composer) {
    $this->composer = $composer;
  }

  /**
   * @param bool|true $required
   * @param bool|false $disablePlugins
   *
   * @return \Composer\Composer
   * @throws \Composer\Json\JsonValidationException
   */
  public function getComposer($required = TRUE, $disablePlugins = FALSE, $disableScripts = NULL) {
    if (NULL === $this->composer) {
      $fileConfig = Composer::$configFile;
      $configFilePath = Composer::$configFilePath;
      try {
        $factory = new Factory();
        $this->composer = $factory->createComposer($this->io, $fileConfig, $disablePlugins, $configFilePath, $disableScripts);
      }
      catch (\InvalidArgumentException $e) {
        if ($required) {
          $this->io->writeError($e->getMessage());
          throw new \InvalidArgumentException($e->getMessage());
        }
      }
      catch (JsonValidationException $e) {
        $errors = ' - ' . implode(PHP_EOL . ' - ', $e->getErrors());
        $message = $e->getMessage() . ':' . PHP_EOL . $errors;
        throw new JsonValidationException($message);
      }
    }

    return $this->composer;
  }

}
