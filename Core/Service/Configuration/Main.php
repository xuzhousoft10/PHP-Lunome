<?php
return array (
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
  'User' => array(
    'enable' => true,
    'class' => 'X\\Module\\Lunome\\Service\\User\\Service',
    'delay' => true,
  ),
);