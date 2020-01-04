<?php
declare(strict_types=1);

namespace Artica\Form;


/**
 * Class Form
 * Base class for Artica Search Forms to use for searching.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Form
 */
abstract class SearchForm extends BaseForm
{
    /**
     * Search and return results.
     * @return mixed
     */
    abstract public function search();
}
