<?php

if ( !function_exists( 'view' ) ) {
    function view( $nombreVista, $params ) {

        extract($params);
        
        $vista = explode('.', $nombreVista);
    
        include_once "./views/{$vista[0]}/{$vista[1]}.php";
    }
}

?>