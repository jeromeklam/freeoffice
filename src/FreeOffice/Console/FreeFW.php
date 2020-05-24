<?php
namespace FreeOffice\Console;

/**
 * FreeOffice commands
 *
 * @author jeromeklam
 */
class FreeFW
{

    /**
     * Retourne une liste de commandes au format FreeFW
     *
     * @return \FreeFW\Console\CommandCollection
     */
    public static function getCommands()
    {
        $commands = new \FreeFW\Console\CommandCollection();
        $paths    = [];
        $paths[]  = __DIR__ . '/../resource/commands/commands.php';
        foreach ($paths as $idx => $onePath)
            if (is_file($onePath)) {{
                $myCommands = @include($onePath);
                if (is_array($myCommands)) {
                    foreach ($myCommands as $idx => $oneCommand) {
                        $myCommand = new \FreeFW\Console\Command();
                        $myCommand
                            ->setName($oneCommand['command'])
                            ->setController($oneCommand['controller'])
                            ->setFunction($oneCommand['function'])
                        ;
                        $commands->addCommand($myCommand);
                    }
                }
            }
        }
        return $commands;
    }
}
