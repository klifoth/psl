<?php

use Symfony\Component\Finder\Finder;

return (new PhpCsFixer\Config())
    ->setFinder(
        Finder::create()
            ->in([
                __DIR__ . '/../src',
                __DIR__ . '/../config',
                __DIR__ . '/../examples',
                __DIR__ . '/../docs',
                __DIR__ . '/../tests',
            ])
    )
    ->setRiskyAllowed(true)
    ->setRules([
        'align_multiline_comment' => true,
        'array_indentation' => true,
        'declare_strict_types' => true,
        // Currently it is not possible to mark all classes as final (exceptions etc.)
        // We can run this fixer periodically on the tests folder only.
        // 'final_class' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'list_syntax' => [
            'syntax' => 'short',
        ],
        'multiline_comment_opening_closing' => true,
        'native_function_casing' => true,
        'no_empty_phpdoc' => true,
        'no_leading_import_slash' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => [
            'imports_order' => ['class', 'function', 'const'],
        ],
        'ordered_interfaces' => true,
        'php_unit_test_annotation' => true,
        'php_unit_test_case_static_method_calls' => [
            'call_type' => 'static',
        ],
        'single_import_per_statement' => true,
        'single_trait_insert_per_statement' => true,
        'static_lambda' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'no_blank_lines_after_phpdoc' => true,
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'phpdoc_indent' => true,
        'phpdoc_line_span' => [
            'const' => 'multi',
            'property' => 'multi',
            'method' => 'multi',
        ],
        'phpdoc_no_alias_tag' => [
            'replacements' => [
                'psalm-var' => 'var',
                'psalm-template' => 'template',
                'psalm-param' => 'param',
                'psalm-return' => 'return',
            ]
        ],
        'phpdoc_order' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_summary' => true,
        'phpdoc_tag_casing' => true,
        'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'no_empty_statement' => true,
        'semicolon_after_instruction' => true,
        'nullable_type_declaration_for_default_null_value' => true,
    ])
    ;
