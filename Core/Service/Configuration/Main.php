<?php
return array (
  'XSession' => 
  array (
    'enable' => true,
    'class' => 'X\\Service\\XSession\\Service',
    'delay' => false,
  ),
  'XDatabase' => 
  array (
    'enable' => true,
    'class' => 'X\\Service\\XDatabase\\Service',
  ),
  'XAction' => 
  array (
    'enable' => true,
    'class' => 'X\\Service\\XAction\\Service',
  ),
  'XView' => 
  array (
    'enable' => true,
    'class' => 'X\\Service\\XView\\Service',
  ),
  'Movie' => 
  array (
    'enable' => true,
    'class' => 'X\\Module\\Lunome\\Service\\Movie\\Service',
  ),
  'Tv' => 
  array (
    'enable' => true,
    'class' => 'X\\Module\\Lunome\\Service\\Tv\\Service',
  ),
  'Comic' => 
  array (
    'enable' => true,
    'class' => 'X\\Module\\Lunome\\Service\\Comic\\Service',
  ),
  'Book' => 
  array (
    'enable' => true,
    'class' => 'X\\Module\\Lunome\\Service\\Book\\Service',
  ),
  'Game' => 
  array (
    'enable' => true,
    'class' => 'X\\Module\\Lunome\\Service\\Game\\Service',
  ),
  'User' => 
  array (
    'enable' => true,
    'class' => 'X\\Module\\Lunome\\Service\\User\\Service',
  ),
  'XLog' => 
  array (
    'enable' => true,
    'delay' => false,
    'class' => 'X\\Service\\XLog\\Service',
  ),
  'QiNiu' => 
  array (
    'enable' => true,
    'delay' => true,
    'class' => 'X\\Service\\QiNiu\\Service',
  ),
  'Configuration' => 
  array (
    'enable' => true,
    'delay' => true,
    'class' => 'X\\Module\\Lunome\\Service\\Configuration\\Service',
  ),
  'Region' => 
  array (
    'enable' => true,
    'delay' => true,
    'class' => 'X\\Module\\Lunome\\Service\\Region\\Service',
  ),
  'People' => 
  array (
    'enable' => true,
    'delay' => true,
    'class' => 'X\\Module\\Lunome\\Service\\People\\Service',
  ),
  'QQ' => 
  array (
    'enable' => true,
    'delay' => true,
    'class' => 'X\\Service\\QQ\\Service',
  ),
  'Sina' => 
  array (
    'enable' => false,
    'delay' => true,
    'class' => 'X\\Service\\Sina\\Service',
  ),
  'XMail' => 
  array (
    'enable' => true,
    'delay' => true,
    'class' => 'X\\Service\\XMail\\Service',
  ),
  'XError' => 
  array (
    'enable' => true,
    'delay' => false,
    'class' => 'X\\Service\\XError\\Service',
  ),
);