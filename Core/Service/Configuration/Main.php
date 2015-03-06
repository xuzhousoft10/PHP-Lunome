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
  'User' => 
  array (
    'enable' => true,
    'class' => 'X\\Module\\Lunome\\Service\\User\\Service',
    'delay' => true,
  ),
  0 => 
  array (
    'enable' => false,
    'class' => 'X\\Service\\XView\\Service',
    'delay' => true,
  ),
  'XView' => 
  array (
    'enable' => false,
    'class' => 'X\\Service\\XView\\Service',
    'delay' => true,
  ),
);