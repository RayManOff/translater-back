<?php

namespace App\Command;

use Stichoza\GoogleTranslate\TranslateClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Gadel Raymanov <raymanovg@gmail.com>
 */

class TestCommand extends Command
{
    protected static $defaultName = 'test:run';

    protected function configure()
    {
        $this->setDescription('Command for testing');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tr = new TranslateClient('en', 'ru');
        $tr->setUrlBase('http://translate.google.cn/translate_a/single');
        var_dump($tr->translate('Hello my friend. My name is Gadel. I live in London and I have never been to Moscow before'));
    }
}