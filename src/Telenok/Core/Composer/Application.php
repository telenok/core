<?php
namespace Telenok\Core\Composer;
use Composer\Json\JsonValidationException;

/**
 * Class extends Composer as embedded
 * 
 * @class Telenok.Core.Composer.Application
 * @extends Composer.Console.Application
 */
class Application extends \Composer\Console\Application {
    
    /**
     * @method setIO
     * Set IO object
     * @member Telenok.Core.Composer.Application
     * @param {Composer.IO.IOInterface} $io
     * For example it can be Composer.IO.ConsoleIO
     * @return {void}
     */
    public function setIO(\Composer\IO\IOInterface $io)
    {
        $this->io = $io;
    }
    
    /**
     * @method getEmbeddedComposer
     * Return embedded composer
     * @member Telenok.Core.Composer.Application
     * @return {Composer.Composer}
     */
    public function getEmbeddedComposer($input = null, $output = null)
    {
        chdir(base_path());

        $input = $input ? $input : new \Symfony\Component\Console\Input\ArrayInput([]);
        $output = $output ? $output : new \Symfony\Component\Console\Output\BufferedOutput();

        $this->setAutoExit(false);

        $this->setIO(new \Composer\IO\ConsoleIO($input, $output, $this->getHelperSet()));

        \Composer\Util\ErrorHandler::register($this->getIO());

        return $this->getComposer(false, true);
    }
}