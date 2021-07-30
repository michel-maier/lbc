<?php
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('.github')
    ->exclude('ansible')
    ->exclude('bin')
    ->exclude('node_modules')
    ->exclude('reports')
    ->exclude('var')
    ->exclude('vendor')
    ->exclude('web')
;


$config = new PhpCsFixer\Config();
return $config->setRules([
    '@Symfony' => true,
    '@PSR2' => true,
    'ordered_imports' => true,
    'array_syntax' => ['syntax' => 'short'],
])
    ->setFinder($finder)
    ;