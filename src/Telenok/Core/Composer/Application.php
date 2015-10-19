<?php
namespace Telenok\Core\Composer;

class Application extends \Composer\Console\Application {
    
    public function setIO(\Composer\IO\IOInterface $io)
    {
        $this->io = $io;
    }
    
    public function getEmbeddedComposer()
    {
        $input = new \Symfony\Component\Console\Input\ArrayInput([]);
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        
        $this->setAutoExit(false);
        $this->setIO(new \Composer\IO\ConsoleIO($input, $output, $this->getHelperSet()));
        
        \Composer\Util\ErrorHandler::register($this->getIO());
        
        chdir(base_path());
        
        return $this->getComposer(false, true);
    }
}