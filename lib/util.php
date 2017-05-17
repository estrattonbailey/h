<?php

namespace h\util;

function attrs($props){
  $output = '';

  foreach($props as $attr => $val){
    $output .= $output."$attr='$val' ";
  }

  return $output;
}

function isArray($arg){
  return 'array' === gettype($arg);
}

function isProps( $arr ){
  $firstVal = array_values($arr)[0];

  return isArray($arr) 
    && 'string' === gettype($firstVal)
    && !preg_match("/<\/|\/>/", $firstVal);
}

function mergeProps($old, $new){
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

function createElement( $tag, $props = false ){
  return function( $args = '' ) use( $tag, $props ){
    if (isArray($args) && isProps($args)){
      $newArgs = mergeProps($props, $args);

      return createElement($tag, $newArgs);
    } else {
      $attrs = $props ? attrs($props) : '';
      $children = '';

      if (isArray($args)){
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
