<?php

namespace controller;

use vue\WebView;

/**
 * Controller is a class that permits to perform actions.
 *
 *
 * @author 22013393
 * @version 1.0
 */
abstract class Controller
{

    /**
     * The Web view.
     */
    protected WebView $webView;

    /**
     * The constructor of the controller.
     *
     * @param WebView $webView The Web view.
     */
    public function __construct(WebView $webView)
    {
        $this->webView = $webView;
    }


    /**
     * Get the Web view.
     *
     * @return WebView The Web view.
     */
    public function getWebView(): WebView
    {
        return $this->webView;
    }


}
