<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var', 'vendor', 'public'])
    ->ignoreVCS(true)
    ->ignoreDotFiles(true)
    ->files()
    ->name('*.php')
    ->notPath([
        'config/bundles.php',
        'config/reference.php',
    ]);
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@autoPHPMigration' => true,
        '@autoPHPMigration:risky' => true,
        '@autoPHPUnitMigration:risky' => true,

        'array_syntax' => ['syntax' => 'short'],
        'list_syntax' => ['syntax' => 'short'],

        'declare_strict_types' => true,
        'strict_comparison' => false,
        'strict_param' => false,

        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'fully_qualified_strict_types' => ['import_symbols' => true],
        'global_namespace_import' => ['import_classes' => false, 'import_functions' => false, 'import_constants' => false],
        'native_function_invocation' => ['include' => ['@all'], 'scope' => 'namespaced', 'strict' => true],
        'ordered_class_elements' => false,
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'self_accessor' => true,
        'no_unneeded_final_method' => true,
        'final_internal_class' => false,
        'void_return' => true,

        'combine_consecutive_issets' => false,
        'combine_consecutive_unsets' => false,
        'no_alias_functions' => true,
        'ternary_to_null_coalescing' => true,
        'simplified_null_return' => true,
        'is_null' => true,
        'explicit_string_variable' => true,
        'string_implicit_backslashes' => true,
        'single_quote' => true,
        'modernize_types_casting' => true,
        'modernize_strpos' => true,
        'get_class_to_class_keyword' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'echo_tag_syntax' => true,
        'heredoc_to_nowdoc' => true,

        'binary_operator_spaces' => ['operators' => ['=' => 'align_single_space', '=>' => 'align_single_space']],
        'single_blank_line_at_eof' => true,
        'concat_space' => ['spacing' => 'one'],
        'blank_line_before_statement' => true,
        'semicolon_after_instruction' => true,
        'no_extra_blank_lines' => ['tokens' => ['extra', 'break', 'continue', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block']],
        'no_spaces_around_offset' => true,
        'cast_spaces' => ['space' => 'single'],
        'lowercase_cast' => true,
        'short_scalar_cast' => true,
        'compact_nullable_type_declaration' => true,
        'method_chaining_indentation' => true,

        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_superfluous_elseif' => true,
        'no_unneeded_braces' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_sprintf' => true,
        'spaces_inside_parentheses' => false,
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_spaces_after_function_name' => true,
        'no_space_around_double_colon' => true,

        'phpdoc_order' => true,
        'phpdoc_separation' => true,
        'phpdoc_align' => false,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_scalar' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'alpha'],
        'phpdoc_trim' => true,
        'phpdoc_indent' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_var_without_name' => true,
        'phpdoc_to_comment' => false,
        'single_line_comment_style' => true,
        'no_superfluous_phpdoc_tags' => false,
        'general_phpdoc_annotation_remove' => ['annotations' => ['author', 'copyright', 'license']],
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_phpdoc' => true,
        'no_empty_comment' => true,
        'no_trailing_whitespace_in_comment' => true,

        'php_unit_strict' => true,
        'php_unit_construct' => true,
        'php_unit_dedicate_assert' => true,
        'php_unit_expectation' => true,
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'php_unit_fqcn_annotation' => true,
    ])
    ->setFinder($finder)
;
