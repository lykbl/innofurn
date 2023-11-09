<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/database')
    ->in(__DIR__ . '/routes')
    ->in(__DIR__ . '/config')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        'binary_operator_spaces' => ['operators' => [
            '=' => 'align_single_space_minimal',
            '=>' => 'align_single_space_minimal',
            '===' => 'align_single_space_minimal',
            '+=' => 'align_single_space_minimal',
        ]],
        'ordered_imports' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'void_return' => true,
        'no_superfluous_phpdoc_tags' => false,
        'php_unit_method_casing' => false,
        'protected_to_private' => false,
        'method_chaining_indentation' => true,
    ))
    ->setFinder($finder)
    ->setUsingCache(true)
    ;
