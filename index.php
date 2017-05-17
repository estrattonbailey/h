<?php

require 'lib/util.php';

function h( $tag ){
  return function( $args ) use ( $tag ) {
    if (\h\util\isArray($args) && \h\util\isProps($args)) {
      return \h\util\createElement( $tag, $args );
    } else {
      $el = \h\util\createElement( $tag );
      return $el( $args );
    }
  };
}
