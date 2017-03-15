<?php

class h_util {
  public static function attrs($props){
    $output = '';

    foreach($props as $attr => $val){
      $output .= $output."$attr='$val' ";
    }

    return $output;
  }

  public static function isArray($arg){
    return 'array' === gettype($arg);
  }

  public static function isProps( $arr ){
    $firstVal = array_values($arr)[0];

    return h_util::isArray($arr) 
      && 'string' === gettype($firstVal)
      && !preg_match("/<\/|\/>/", $firstVal);
  }

  public static function mergeProps($old, $new){
    $arr = [];

    foreach($old as $oldkey => $oldval){
      foreach($new as $newkey => $newval){
        if ($oldkey === $newkey){
          if ($oldkey === 'class' || $oldkey === 'style'){
            $arr[$oldkey] = $oldval.' '.$newval;
          } else {
            $arr[$oldkey] = $newval;
          }
        } else {
          $arr[$newkey] = $newval;
        }
      }
    }

    return $arr;
  }

  public static function createElement( $tag, $props = false ){
    return function( $args = '' ) use( $tag, $props ){
      if (h_util::isArray($args) && h_util::isProps($args)){
        $newArgs = h_util::mergeProps($props, $args);

        return h_util::createElement($tag, $newArgs);
      } else {
        $attrs = $props ? h_util::attrs($props) : '';
        $children = '';

        if (h_util::isArray($args)){
          foreach($args as $index => $child){
            $children .= $child;
          } 
        } else {
          $children = $args;
        }

        return "<{$tag} {$attrs}>{$children}</${tag}>";
      }
    };
  }
}

function h( $tag ){
  return function( $args ) use ( $tag ) {
    if (h_util::isArray($args) && h_util::isProps($args)) {
      return h_util::createElement( $tag, $args );
    } else {
      return h_util::createElement( $tag )( $args );
    }
  };
}
