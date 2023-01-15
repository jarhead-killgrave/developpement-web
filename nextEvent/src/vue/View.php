<?php

namespace vue;

/**
 * A view is an object that permits to display a page. It is used to display the pages of the application.
 *
 * @author 22013393
 * @version 1.0
 */
interface View
{

    /**
     * Display the page.
     *
     * @return void
     */
    public function render(): void;


}
