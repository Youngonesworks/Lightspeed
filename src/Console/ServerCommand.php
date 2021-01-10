<?php


namespace YoungOnes\Lightspeed\Console;


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YoungOnes\Lightspeed\Server\Server;

class ServerCommand extends Command
{
    protected $signature = 'lightspeed:server
                            {action=help : start|help}
                            {--D|daemonize : Run as a daemon}';

    protected $description = 'Actions concerning the lightspeed server.';

    public function handle()
    {
        try {
            $action = $this->argument('action');

            switch ($action) {
                case 'start':
                    return $this->start();
                default:
                    return $this->showHelp();
            }
        } catch (\Exception $e) {
            $error = sprintf(
                'Uncaught exception "%s"([%d]%s) at %s:%s, %s%s',
                get_class($e),
                $e->getCode(),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                PHP_EOL,
                $e->getTraceAsString()
            );
            $this->error($error);
            return 1;
        }
    }

    private function showHelp(): int
    {
        $help = <<<EOS
            Usage:
              [%s] artisan lightspeed:server <action> [options]
            Arguments:
              action                start|help
            EOS;

        $this->info(sprintf($help, PHP_BINARY));
        return 0;
    }

    private function start()
    {

        $uri = config('lightspeed_server.port');
        $hostname = config('lightspeed_server.host');

        if (!empty($hostname)) {
            $uri = sprintf('%s:%s', $hostname, $uri);
        }

        $this->info(sprintf('Lightspeed TCP server running on tcp://%s', $uri));
        $this->info('Ctrl + C to exit');

        new Server($uri);

        return 0;
    }

}
