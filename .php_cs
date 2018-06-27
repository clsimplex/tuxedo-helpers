<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('resources')
    ->exclude('node_modules')
    ->exclude('storage')
    ->exclude('public')
    ->exclude('bootstrap')
    ->in(__DIR__);

return PhpCsFixer\Config::create()
  ->setRiskyAllowed(true)
  ->setRules(array(
    'array_syntax'                        => ['syntax' => 'short'],
    'blank_line_after_namespace'          => true,
    'blank_line_after_opening_tag'        => true,
    'encoding'                            => true,
    'cast_spaces'                         => true,
    'concat_space'                        => ['spacing' => 'one'],
    'combine_consecutive_unsets'          => true,
    'indentation_type'                    => true,
    'lowercase_cast'                      => true,
    'no_empty_comment'                    => true,
    'no_trailing_whitespace'              => true,
    'no_trailing_whitespace_in_comment'   => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_unused_imports'                   => true,
    'not_operator_with_space'             => true,
    'ordered_imports'                     => true,
    'phpdoc_add_missing_param_annotation' => true,
    'phpdoc_align'                        => true,
    'phpdoc_indent'                       => true,
    'phpdoc_order'                        => true,
    'psr4'                                => true,
    'visibility_required'                 => true,
    'whitespace_after_comma_in_array'     => true,
  ))
  ->setFinder($finder);
