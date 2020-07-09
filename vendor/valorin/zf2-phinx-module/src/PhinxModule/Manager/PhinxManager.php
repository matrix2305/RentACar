<?php
/**
 * ZF2 Phinx Module
 *
 * @link      https://github.com/valorin/zf2-phinx-module
 * @copyright Copyright (c) 2012-2013 Stephen Rees-Carter <http://stephen.rees-carter.net/>
 * @license   See LICENCE.txt - New BSD License
 */

namespace PhinxModule\Manager;

use Phinx\Console\PhinxApplication;
use Symfony\Component\Yaml\Yaml;
use Zend\Config\Config;
use Zend\Config\Writer\PhpArray;
use Zend\Console\Adapter\AbstractAdapter as ConsoleAdapter;
use Zend\Console\ColorInterface;
use Zend\Console\Prompt;

class PhinxManager implements ColorInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Console
     */
    protected $console;


    /**
     * Constructor
     *
     * @param ConsoleAdapter $console
     * @param array          $config
     * @throws RuntimeException
     */
    public function __construct(ConsoleAdapter $console, $config = array())
    {
        $this->config  = $config;
        $this->console = $console;
    }


    /**
     * Interactive database setup.
     * WILL OUTPUT VIA CONSOLE!
     *
     * @param  boolean $overwrite Overwrite existing config
     * @param  boolean $quiet     Fail quietly
     * @return string
     */
    public function setup($overwrite = false, $quiet = false)
    {
        /**
         * Output console usage
         */
        if (!$this->console) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $this->console->writeLine("ZF2 Phinx Module - Interactive Setup", self::GREEN);


        /**
         * Check for existing config
         */
        $zfConfig    = $this->config['phinx-module']['zf2-config'];
        $phinxConfig = $this->config['phinx-module']['phinx-config'];
        if (!$overwrite && (file_exists($zfConfig) || file_exists($phinxConfig))) {
            if (!$quiet) {
                $this->console->writeLine("Existing config file(s) found, unable to continue!", self::LIGHT_RED);
                $this->console->writeLine("Use the --overwrite flag to replace existing config.", self::LIGHT_RED);
            } else {
                $this->console->writeLine("Config exists, skipping setup.");
            }
            return;
        }


        /**
         * Ask questions
         */
        $loop = true;
        while ($loop) {
            $this->console->writeLine("MySQL Database connection details:");
            $host   = Prompt\Line::prompt("Hostname? [localhost] ", true) ?: 'localhost';
            $port   = Prompt\Line::prompt("Port? [3306] ", true) ?: 3306;
            $user   = Prompt\Line::prompt("Username? [isle] ", true) ?: 'isle';
            $pass   = Prompt\Line::prompt("Password? ");
            $dbname = Prompt\Line::prompt("Database name? [isle] ", true) ?: 'isle';

            $loop = !Prompt\Confirm::prompt("Save these details? [y/n]");
        }


        /**
         * Build config
         */
        $config = new Config(
            array(
                'db' => array(
                    'dsn'      => "mysql:dbname={$dbname};host={$host}",
                    'username' => $user,
                    'password' => $pass,
                    'port'     => $port,
                ),
            )
        );
        $writer = new PhpArray();
        $writer->toFile($zfConfig, $config);

        $this->console->writeLine();
        $this->console->writeLine("ZF2 Config file written: {$zfConfig}");


        /**
         * Write Phinx config
         */
        $migrations = $this->config['phinx-module']['migrations'];
        $this->writePhinxConfig('mysql', $host, $user, $pass, $dbname, $port, $migrations);

        $this->console->writeLine("Phinx Config file written: {$phinxConfig}");
    }


    /**
     * Sync database credentials with phinx.yml config
     *
     * @return string
     * @throws RuntimeException
     */
    public function sync($migrations = null)
    {
        /**
         * Check for db config section
         */
        if (!isset($this->config['db'])) {
            throw new \RuntimeException("Cannot find 'db' config section, unable to sync Phinx config!");
        }


        /**
         * Extract details from DSN string
         */
        $dsn = "/^(\w+):dbname=(\w+);host=(\w+)$/i";
        if (!isset($this->config['db']['dsn'])
            || !preg_match($dsn, $this->config['db']['dsn'], $matches)) {
            throw new \RuntimeException("Unable to parse 'db' => 'dsn' connection string!");
        }


        /**
         * Load variables
         */
        $migrations = $migrations ?: $this->config['phinx-module']['migrations'];
        $port       = isset($this->config['db']['port']) ? $this->config['db']['port'] : 3306;


        /**
         * Write Phinx Config
         */
        $this->writePhinxConfig(
            $matches[1],
            $matches[3],
            $this->config['db']['username'],
            $this->config['db']['password'],
            $matches[2],
            $port,
            $migrations
        );

        return "Phinx config file written: {$this->config['phinx-module']['phinx-config']}\n";
    }


    /**
     * Command pass-through
     *
     */
    public function command()
    {
        /**
         * Sync
         */
        $this->sync();


        /**
         * Update argv's
         */
        $argv = $_SERVER['argv'];
        array_shift($_SERVER['argv']);


        /**
         * Add config param as required
         */
        if (isset($_SERVER['argv'][1]) && !in_array($_SERVER['argv'][1], Array('init', 'list'))) {
            $_SERVER['argv'][] = "--configuration={$this->config['phinx-module']['phinx-config']}";
        }


        /**
         * Run Phinx
         */
        include getcwd()."/vendor/bin/phinx";


        /**
         * Shift argv's
         */
        $_SERVER['argv'] = $argv;
    }


    /**
     * Writes the Phinx config file with the specified details
     *
     * @param string $adapter
     * @param string $host
     * @param string $dbname
     * @param string $user
     * @param string $pass
     * @param string $port
     * @param string $migrations
     */
    protected function writePhinxConfig($adapter, $host, $user, $pass, $dbname, $port, $migrations)
    {
        /**
         * Build phinx config
         */
        $phinx = Array(
            'paths'        => Array('migrations' => $migrations),
            'environments' => Array(
                'default_migration_table' => 'phinxlog',
                'default_database'        => 'zf2',
                'zf2'                     => Array(
                    'adapter' => $adapter,
                    'host'    => $host,
                    'name'    => $dbname,
                    'user'    => $user,
                    'pass'    => $pass,
                    'port'    => $port,
                ),
            ),
        );


        /**
         * Write YAML
         */
        $yaml = Yaml::dump($phinx);

        file_put_contents($this->config['phinx-module']['phinx-config'], $yaml);
    }
}
