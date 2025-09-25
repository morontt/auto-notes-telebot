<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/web',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        //'declare_strict_types' => true,
        'blank_line_after_opening_tag' => false,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'ordered_imports' => [
            'imports_order' => ['class', 'function', 'const'],
            'sort_algorithm' => 'alpha'
        ],
        'single_quote' => true,
        'no_extra_blank_lines' => [
            'tokens' => ['attribute', 'case', 'continue', 'curly_brace_block', 'default', 'extra',
                'parenthesis_brace_block', 'square_brace_block', 'switch', 'throw', 'use']
        ],
        'blank_line_before_statement' => ['statements' => ['return']],
        'global_namespace_import' => ['import_classes' => true],
        'no_unused_imports' => true,
        'ordered_class_elements' => true,
        'no_empty_phpdoc' => true,
        'phpdoc_trim' => true,
        'heredoc_indentation' => true,
        'array_indentation' => true,
    ])
    ->setFinder($finder);
