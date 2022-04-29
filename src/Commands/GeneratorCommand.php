<?php

namespace AliRahimiCoder\LaravelDataMigrations\Commands;

use Illuminate\Console\Command;

abstract class GeneratorCommand extends Command
{
    abstract protected function getContents(): string;

    abstract protected function getDestinationFilePath(): string;

    public function handle(): int
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        $directory = dirname($path);

        if (!file_exists($directory)){
            mkdir($directory, 0777, true);
        }

        $contents = $this->getContents();

        $overwriteFile = $this->hasOption('force') && $this->option('force');

        $fileExists = file_exists($path);

        if (!$fileExists || $overwriteFile) {
            file_put_contents($path, $contents);
            $this->info("Created : {$path}");
            return self::SUCCESS;
        } else {
            $this->error("File : {$path} already exists.");
            return self::FAILURE;
        }
    }
}