<?php
/**
 * Created by PhpStorm.
 * User: francisco
 * Date: 2019-03-10
 * Time: 01:00
 */

namespace Controllers;

use Interop\Container\ContainerInterface;
use Models\Collaborator;
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

        if (array_key_exists('message', $body)) {
            $this->init(
                $this->container->get('settings')['telegram']['FlisolDFAdministracaoBot']['token'],
                $body['message']['chat']['id']
            );
            $message = $body['message']['text'];

            // Available bot commands
            $commands = [
                // Start of bot
                'start',

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

                'participants',

                'collaborators',
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
                ],
                'participants' => [
//                'total',
                ],
                'collaborators' => [
//                'total',
                ],
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
        } else {
            $this->unknown();
        }
    }

    public function start()
    {
        $message = 'Seja bem vindo ao robô da administração do FlisolDF!';
        $this->sendMessage($message);

        $this->help();
    }

    public function help()
    {
        $message = '<strong>Ajuda Geral</strong>' . chr(10) . chr(10);
        $message .= '<strong>/help server</strong>' . chr(10) . '  - List the server related commands' . chr(10) . chr(10);
        $message .= '<strong>/talks</strong>' . chr(10) . '  - Lista as palestras' . chr(10) . chr(10);
        $message .= '<strong>/talks total</strong>' . chr(10) . '  - Exibe o total de palestras' . chr(10) . chr(10);
        $message .= '<strong>/participants total</strong>' . chr(10) . '  - Exibe o total de participantes (confirmados)' . chr(10) . chr(10);
        $message .= '<strong>/collaborators total</strong>' . chr(10) . '  - Exibe o total de colaboradores';

        return $this->sendMessage($message);
    }

    public function talks($args)
    {
        // Create connection. Necessary to stablish a connection.
        $db = $this->container->get('db');

        if (array_key_exists(0, $args)) {
            if ($args[0] === 'total') {
                $talks = Talk::where('edition_id', 15)
                    ->get();

                $message = 'Total de palestras: <strong>' . count($talks) . '</strong>';

                return $this->sendMessage($message);
            }
        }

        // Else
        $talks = Talk::join('speaker_talk', 'speaker_talk.talk_id', '=', 'talk.id')
            ->join('person', 'person.id', '=', 'speaker_talk.speaker_id')
            ->where('talk.edition_id', 15)
            ->get();

        $message = '<strong>Listagem das palestras</strong>' . chr(10) . chr(10);

        $i = 0;
        foreach ($talks as $talk) {
            $message .= '<strong>' . (++$i) . '. ' . $talk->title . '</strong> - <em>' . $talk->name . '</em>' . chr(10);
        }

        return $this->sendMessage($message);
    }

    public function participants($args)
    {
        // Create connection. Necessary to stablish a connection.
        $db = $this->container->get('db');

        if (array_key_exists(0, $args)) {
            if ($args[0] === 'total') {
//                $talks = Talk::where('edition_id', 15)
//                    ->get();
//
//                $message = 'Total de palestras: <strong>' . count($talks) . '</strong>';
//
//                return $this->sendMessage($message);

                $confirmed = 0;

                // Set a user agent. This basically tells the server that we are using Chrome ;)
                define('USER_AGENT', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.2309.372 Safari/537.36');

                // Where our cookie information will be stored (needed for authentication).
                define('COOKIE_FILE', './../tmp/cookie' . microtime() . '.txt');

                // An associative array that represents the required form fields.
                // You will need to change the keys / index names to match the name of the form
                // fields.
                $postValues = [
                    '_method' => 'POST',
                    'data[_Token][key]' => 'dea5c779a15db160c946a418fbbbf8db027525a2655405f497cfe7d7e767e97dc6e298a9b0431337443f5fbc9020d93ba58b44be5d2b01f92771ccb4895775e8',
                    'data[User][username]' => $this->container->get('settings')['doity']['authentication']['username'],
                    'data[User][password]' => $this->container->get('settings')['doity']['authentication']['password'],
                    'data[_Token][fields]' => '614b91fd9881870a3f1a968120fbf154615ed13b%3A',
                    'data[_Token][unlocked]' => ''
                ];

                // Initiate cURL.
                $curl = curl_init();

                // Set the URL that we want to send our POST request to. In this
                // case, it's the action URL of the login form.
                curl_setopt($curl, CURLOPT_URL, $this->container->get('settings')['doity']['url']['login']);

                // Tell cURL that we want to carry out a POST request.
                curl_setopt($curl, CURLOPT_POST, true);

                // Set our post fields / date (from the array above).
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postValues));

                // We don't want any HTTPS errors.
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                // Where our cookie details are saved. This is typically required
                // for authentication, as the session ID is usually saved in the cookie file.
                curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIE_FILE);

                // Sets the user agent. Some websites will attempt to block bot user agents.
                // Hence the reason I gave it a Chrome user agent.
                curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);

                // Tells cURL to return the output once the request has been executed.
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                // Allows us to set the referer header. In this particular case, we are
                // fooling the server into thinking that we were referred by the login form.
                curl_setopt($curl, CURLOPT_REFERER, $this->container->get('settings')['doity']['url']['event']);

                // Do we want to follow any redirects?
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);

                // Execute the login request.
                echo curl_exec($curl);

                // Check for errors!
                if (curl_errno($curl)) {
                    throw new Exception(curl_error($curl));
                }

                // We should be logged in by now. Let's attempt to access a password protected page
                curl_setopt($curl, CURLOPT_URL, $this->container->get('settings')['doity']['url']['event']);

                // Use the same cookie file.
                curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIE_FILE);

                // Use the same user agent, just in case it is used by the server for session validation.
                curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);

                // We don't want any HTTPS / SSL errors.
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                // Execute the GET request and print out the result.
                $html = curl_exec($curl);

                // DONE: Fix E_WARNING on load html
                $previous_value = libxml_use_internal_errors(true);
                $doc = new \DOMDocument();
                $doc->strictErrorChecking = false;
                $doc->loadHTML($html);
                libxml_clear_errors();
                libxml_use_internal_errors($previous_value);

                $xpath = new \DOMXpath($doc);
                $nodelist = $xpath->query('//div[@class="basicas"]');

                if (!is_null($nodelist)) {
                    if ($nodelist->count() > 0) {
                        $node = $nodelist->item(0);
                        if (!is_null($node)) {

                            $xpath = new \DOMXpath($doc);
                            $nodelist = $xpath->query('//td[@class="td-valor"]');

                            if (!is_null($nodelist)) {
                                if ($nodelist->count() > 0) {
                                    $node = $nodelist->item(2);
                                    if (!is_null($node)) {

                                        $confirmed = (int)$node->nodeValue;
                                    }
                                }
                            }
                        }
                    }
                }

                $message = 'Total de participantes (confirmados): <strong>' . $confirmed . '</strong>';

                return $this->sendMessage($message);
            }
        }

//        // Else
//        $talks = Talk::join('speaker_talk', 'speaker_talk.talk_id', '=', 'talk.id')
//            ->join('person', 'person.id', '=', 'speaker_talk.speaker_id')
//            ->where('talk.edition_id', 15)
//            ->get();
//
//        $message = '<strong>Listagem das palestras</strong>' . chr(10) . chr(10);
//
//        foreach ($talks as $talk) {
//            $message .= '<strong>' . $talk->id . '. ' . $talk->title . '</strong> - <em>' . $talk->name . '</em>' . chr(10);
//        }
//
//        return $this->sendMessage($message);
    }

    public function collaborators($args)
    {
        // Create connection. Necessary to stablish a connection.
        $db = $this->container->get('db');

        if (array_key_exists(0, $args)) {
            if ($args[0] === 'total') {
                $collaborators = Collaborator::where('edition_id', 15)
                    ->get();

                $message = 'Total de colaboradores: <strong>' . count($collaborators) . '</strong>';

                return $this->sendMessage($message);
            }
        }

//        // Else
//        $talks = Talk::join('speaker_talk', 'speaker_talk.talk_id', '=', 'talk.id')
//            ->join('person', 'person.id', '=', 'speaker_talk.speaker_id')
//            ->where('talk.edition_id', 15)
//            ->get();
//
//        $message = '<strong>Listagem das palestras</strong>' . chr(10) . chr(10);
//
//        $i = 0;
//        foreach ($talks as $talk) {
//            $message .= '<strong>' . (++$i) . '. ' . $talk->title . '</strong> - <em>' . $talk->name . '</em>' . chr(10);
//        }
//
//        return $this->sendMessage($message);
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