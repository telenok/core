<?php

namespace Telenok\Core\Support\Migrations;

abstract class Migration extends \Illuminate\Database\Migrations\Migration
{
    public function up()
    {
        (new \Symfony\Component\Console\Output\ConsoleOutput(
            \Symfony\Component\Console\Output\ConsoleOutput::VERBOSITY_NORMAL
        ))->writeln(get_class($this));
    }
}
