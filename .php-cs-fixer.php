<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->exclude(['vendor', 'var'])
    ->notPath('config/bundles.php')
    ->files()
    ->name('*.php')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
