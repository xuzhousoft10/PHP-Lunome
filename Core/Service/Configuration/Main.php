<?php
return array (
  'XRequest' => 
  array (
    'class' => 'X\\Service\\XRequest\\Service',
    'enable' => true,
    'delay' => false,
  ),
  'XAction' => 
  array (
    'class' => 'X\\Service\\XAction\\Service',
    'enable' => true,
    'delay' => true,
  ),
  'XDatabase' => 
  array (
    'enable' => true,
    'class' => 'X\\Service\\XDatabase\\Service',
    'delay' => true,
  ),
  'XView' => 
  array (
    'enable' => false,
    'class' => 'X\\Service\\XView\\Service',
    'delay' => true,
  ),
  'Qiniu' =>
  array (
    'enable' => true,
    'class' => 'X\\Service\\QiNiu\\Service',
    'delay' => true,
  ),
  'Region' =>
  array (
    'enable' => true,
    'class' => 'X\\Module\\Lunome\\Service\\Region\\Service',
    'delay' => true,
  ),
  'XSession' =>
  array (
    'enable' => true,
    'class' => 'X\\Service\\XSession\\Service',
    'delay' => false,
  ),
  'QQ' =>
  array (
    'enable' => true,
    'class' => 'X\\Service\\QQ\\Service',
    'delay' => true,
  ),
  'Configuration' =>
  array (
    'enable' => true,
    'class' => 'X\\Module\\Lunome\\Service\\Configuration\\Service',
    'delay' => true,
  ),
    'XLog' =>
    array (
        'enable' => true,
        'class' => 'X\\Service\\XLog\\Service',
        'delay' => true,
    ),
    'Account' =>
    array (
        'enable' => true,
        'class' => 'X\\Module\\Account\\Service\\Account\\Service',
        'delay' => true,
    ),
    'Movie' => array(
        'enable' => true,
        'delay' => true,
        'class' => 'X\\Module\\Movie\\Service\\Movie\\Service',
    ),
);