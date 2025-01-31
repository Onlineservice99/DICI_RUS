<?php

namespace Yogarine\Composer;

use Composer\Autoload\AutoloadGenerator;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Override the autoloader generator.
 *
 * This might conflict with other plugins dealing with the autoloader as well.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * @author Alwin Garside <alwin@garsi.de>
 */
class DoctrineAnnotationAutoloadPlugin implements PluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $autoloadGenerator = new Autoload\DoctrineAutoloadGenerator($composer->getEventDispatcher(), $io);

        $composer->setAutoloadGenerator($autoloadGenerator);
    }

    /**
     * {@inheritdoc}
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
        $autoloadGenerator = new AutoloadGenerator($composer->getEventDispatcher(), $io);

        $composer->setAutoloadGenerator($autoloadGenerator);
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
        // Nothing to do here.
    }
}
