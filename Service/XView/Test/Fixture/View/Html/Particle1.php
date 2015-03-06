<?php
foreach ( get_defined_vars() as $key => $value ) {
    if ( 'this' === $key ) {
        continue;
    }
    echo $key.':'.$value."\n";
}