<?php
$localCommands = [
    /**
     * ########################################################################
     * Routes Office
     * ########################################################################
     */
    'freeoffice.test' => [
        'command'    => 'office::test',
        'controller' => 'FreeOffice::Command::Docx',
        'function'   => 'test'
    ],
];

return $localCommands;