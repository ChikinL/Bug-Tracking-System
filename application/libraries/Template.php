<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 *  This template library can be used to automatically build 
 *    views with a header, navigation and footer 
 * 
 * 
 *    Usage: $this->template->show('view', $args, $bool); 
 *    Note: make sure to include in autoload.php 
 * 
 * 
 */
class Template
{

    function show($view, $args = NULL, $hasNavigationBar)
    {
        $CI = & get_instance();
        if ($hasNavigationBar == TRUE)
        {
            $CI->load->view('header', $args);
            $CI->load->view('navigation', $args);
            $CI->load->view($view, $args);
            $CI->load->view('footer', $args);
        }
        elseif ($hasNavigationBar == FALSE)
        {
            $CI->load->view('header', $args);
            $CI->load->view($view, $args);
            $CI->load->view('footer', $args);
        }
    }

}
