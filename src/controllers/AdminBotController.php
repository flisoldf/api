<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 2019-03-10
 * Time: 01:00
 */

namespace Controllers;

use Interop\Container\ContainerInterface;
use Models\Talk;
use Slim\Http\Request;
use Slim\Http\Response;

class AdminBotController extends BotController
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $args = [])
    {
        $body = $request->getParsedBody();
        $this->init(
            $this->container->get('settings')['telegram']['FlisolDFAdministracaoBot']['token'],
            $body['message']['chat']['id']
        );
        $message = $body['message']['text'];

        // Available bot commands
        $commands = [
            // General Commands
            'help',

            // Server Commands
            'server',

            //Alias for /server uptime
            'uptime',

            // Alias for /server uname
            'uname',

            // Alias for /server who
            'who',

            // Alias for /server disk
            'disk',

            'talks',
        ];

        $arguments = [
            // Server
            'server' => [
                'uptime',
                'uname',
                'who',
                'disk',
            ],
            'help' => [
                'server',
            ],
            'talks' => [
//                'total',
            ]
        ];

        // Aliases for commands
        $alias = [
            'uptime' => 'server',
            'uname' => 'server',
            'who' => 'server',
            'disk' => 'server',
        ];

        $args = explode(' ', trim($message));

        $command = ltrim(array_shift($args), '/');
        $method = '';

        if (isset($args[0]) && in_array($args[0], $arguments[$command])) {
            $method = array_shift($args);
        } elseif (in_array($command, array_keys($alias))) {
            $method = $command;
            $command = $alias[$command];
        }

        if (!in_array($command, $commands)) {
            $this->unknown();
        } elseif (isset($arguments[$command]) && in_array($method, $arguments[$command])) {
            $this->{$method}($args);
            die();
        } else if (in_array($command, $commands)) {
            $this->{$command}($args);
        } else {
            $this->unknown();
        }
    }

    public function help()
    {
        $message = "<b>Ajuda Geral</b>" . chr(10) . chr(10);
        $message .= "<b>/help server</b>" . chr(10) . "  - List the server related commands" . chr(10) . chr(10);
        $message .= "<b>/talks total</b>" . chr(10) . "  - Lista o total de palestras";

        return $this->sendMessage($message);
    }

    public function talks($args)
    {
        // Create connection. Necessary to stablish a connection.
        $db = $this->container->get('db');

        if ($args[0] === 'total') {
            $talks = Talk::where('edition_id', 15)
//            ->orderBy('name', 'desc')
//            ->take(10)
                ->get();

            $message = 'Total de Palestras: <strong>' . count($talks) . '</strong>';

            return $this->sendMessage($message);
        }
    }

    public function server()
    {
        $message = "<b>Server related commands</b>" . chr(10) . chr(10);
        $message .= "<b>/server uptime</b>" . chr(10) . "  - Retrieves the uptime of the server (alias /uptime)" . chr(10) . chr(10);
        $message .= "<b>/server uname</b>" . chr(10) . "  - Retrieves the server name, build and kernel (alias /uname)" . chr(10) . chr(10);
        $message .= "<b>/server who</b>" . chr(10) . "  - Retrieves the current sessions on the server (alias /who)" . chr(10) . chr(10);
        $message .= "<b>/server disk</b>" . chr(10) . " - Retrieves the disk information like space used/available (alias /disk)";

        return $this->sendMessage($message);
    }

    public function uptime()
    {
        return $this->sendMessage("Server uptime:" . exec('uptime'));
    }

    public function uname()
    {
        return $this->sendMessage(exec('uname -a'));
    }

    public function who()
    {
        exec('who', $serverwho);

        $output = "No active sessions on server at the moment.";
        $i = 0;

        if (count($serverwho) > 0) {
            $output = "Current sessions on server:" . chr(10);
            foreach ($serverwho as $line) {
                $output .= "#" . ++$i . " " . $line . chr(10);
            }
        }

        return $this->sendMessage($output);
    }

    public function disk()
    {
        exec('df -hT /home', $serverspace);
        if (count($serverspace) > 0) {
            $parsed = array_values(array_filter(explode(" ", $serverspace[1])));
            return $this->sendMessage("<b>Filesystem</b>: " . $parsed[1] . chr(10) . "<b>Size</b>: " . $parsed[2] . chr(10) . "<b>Used</b>: " . $parsed[3] . " (" . $parsed[5] . ")" . chr(10) . "<b>Available</b>: " . $parsed[4]);
        } else {
            return $this->sendMessage("Error executing the requested command.");
        }
    }

}