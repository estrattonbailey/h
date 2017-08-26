<?php

namespace h\util;

/*
 * Reduce array of attribute/value pairs
 * into a single is_string
 */
function attrs($props){
  return array_reduce(array_keys($props), function($res, $key) use ($props) {
    $res .= "$key='$props[$key]'";
    return $res;
  }, '');
}

/*
 * Check if an $args value isProps
 * an array of new props. If this Check
 * fails, it either means we have a String
 * child, or an array of components
 * (hence the regex).
 */
function isProps( $arr ){
  $test = array_values($arr)[0];

  return is_array($arr) 
    && is_string($test)
    && !preg_match("/<\/|\/>/", $test);
}

/*
 * Merge two arrays, merging existing
 * keys and values 
 */
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
    /*
     * If a user passes another set of props,
     * merge them and and call createElement again
     * (bc we're still waiting for a $children prop)
     */
    if (is_array($args) && isProps($args)){
      $newArgs = mergeProps($props, $args);
      return createElement($tag, $newArgs);
    }
    /*
     * If it's not new props, OR the props failed
     * the isProps() check, we're ready to return
     * a component, or array of components.
     */
    else {
      $attrs = $props ? attrs($props) : '';
      $children = '';

      if (is_array($args)){
        /*
         * Concat an array of child components
         */
        foreach($args as $index => $child){
          $children .= $child;
        } 
      } else {
        /*
         * String as a child
         */
        $children = $args;
      }

      return "<{$tag} {$attrs}>{$children}</${tag}>";
    }
  };
}
