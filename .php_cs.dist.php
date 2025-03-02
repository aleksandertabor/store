<?php

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'align_multiline_comment' => true,
        'array_indentation' => true,
        'array_push' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'concat_space' => true,
        'class_definition' => true,
        'date_time_immutable' => true,
        'declare_strict_types' => true,
        'explicit_string_variable' => true,
        'fully_qualified_strict_types' => true,
        'mb_str_functions' => true,
        'logical_operators' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        "new_with_braces" => true,
        'ordered_traits' => true,
        "no_empty_comment" => true,
        'use_arrow_functions' => true,
        'not_operator_with_successor_space' => true,
        'method_chaining_indentation' => true,
        'trailing_comma_in_multiline' => [
            'elements' => [
                'arrays',
                'arguments',
                'parameters',
            ],
        ],
        'phpdoc_scalar' => true,
        'unary_operator_spaces' => true,
        'binary_operator_spaces' => true,
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_var_without_name' => true,
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
            ],
        ],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
            'keep_multiple_spaces_after_comma' => true,
        ],
        'no_whitespace_before_comma_in_array' => true,
        'short_scalar_cast' => true,
        'single_trait_insert_per_statement' => true,
        'ternary_to_elvis_operator' => true,
        'ternary_to_null_coalescing' => true,
        'types_spaces' => true,
        'visibility_required' => [
            'elements' => [
                'method',
                'property',
                'const',
            ],
        ],
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->exclude('node_modules')
            ->exclude('storage')
            ->exclude('bootstrap')
            ->notPath('_ide_helper.php')
            ->notPath('_ide_helper_models.php')
            ->in(__DIR__),
    );
