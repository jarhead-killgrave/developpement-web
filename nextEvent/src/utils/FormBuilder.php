<?php

namespace utils;

/**
 * FormBuilder class is used to build forms
 *
 * @author    22013393
 * @version 1.0
 */
class FormBuilder
{

    /**
     * The form's action
     * @var string
     */
    private string $action;

    /**
     * The form's method
     * @var string
     */
    private string $method;

    /**
     * The form's fields
     * @var array
     */
    private array $fields;

    /**
     * The form's errors
     * @var array
     */
    private array $errors;

    /**
     * The form's data
     * @var array
     */
    private array $data;

    /**
     * The form's submit button
     * @var string
     */
    private string $submit;

    /**
     * FormBuilder constructor.
     *
     * @param string $action The form's action
     * @param string $method The form's method
     */
    public function __construct(string $action, string $method)
    {
        $this->action = $action;
        $this->method = $method;
        $this->fields = [];
        $this->errors = [];
        $this->data = [];
        $this->submit = "";
    }

    /**
     * Add a field to the form
     *
     * @param string $name The field's name
     * @param string $type The field's type
     * @param string $label The field's label
     * @param string $placeholder The field's placeholder
     * @param string $value The field's value
     * @param string $class The field's class
     * @param string $id The field's id
     * @param string $rows The field's rows
     */
    public function addField(string $name, string $type, string $label, string $placeholder, string $value, string $class = "", string $id = "", string $rows = "")
    {
        $this->fields[] = [
            "name" => $name,
            "type" => $type,
            "label" => $label,
            "placeholder" => $placeholder,
            "value" => $value,
            "class" => $class,
            "id" => $id,
            "rows" => $rows
        ];
    }

    /**
     * Add an error to the form
     *
     * @param string $error The error to add
     */
    public function addError(string $error)
    {
        $this->errors[] = $error;
    }

    /**
     * Add errors to the form
     * @param array $errors The errors to add
     */
    public function addErrors(array $errors)
    {
        $this->errors = array_merge($this->errors, $errors);
    }

    /**
     * Open fieldset
     * @param string $legend The fieldset's legend
     */
    public function openFieldset(string $legend)
    {
        $this->fields[] = [
            "legend" => $legend
        ];
    }

    /**
     * Close fieldset
     */
    public function closeFieldset()
    {
        $this->fields[] = [
            "close" => true
        ];
    }

    /**
     * Add a submit button to the form
     *
     * @param string $value The submit button's value
     */
    public function addSubmit(string $value): void
    {
        $this->submit = $value;
    }

    /**
     * Build the form
     *
     * @return string The form
     */
    public function build(): string
    {
        $form = "<form class='form' enctype='multipart/form-data' action='$this->action' method='$this->method'>";
        foreach ($this->fields as $field) {
            $form .= "<div class='form-group'>\n";
            $form .= "\t<label for='$field[name]'>$field[label]\n";
            $form .= "<br>";
            switch ($field["type"]) {
                case "textarea":
                    $form .= "<textarea class='$field[class]' name='$field[name]'  placeholder='$field[placeholder]' rows='$field[rows]'>$field[value]</textarea>";
                    break;
                case "select":
                    $form .= "<select class='$field[class]' name='$field[name]' id='$field[name]'>";
                    foreach ($field["options"] as $option) {
                        $form .= "<option value='$option[value]'>$option[label]</option>";
                    }
                    $form .= "</select>";
                    break;
                default:
                    $form .= "<input class='$field[class]' type='$field[type]' name='$field[name]'  placeholder='$field[placeholder]' value='$field[value]' id='$field[name]'>";
                    break;
            }
            $form .= "</label>\n";
            // Check if the field has an error
            if (array(array_key_exists($field["name"], $this->errors))) {
                $form .= "<br>";
                $form .= "<small class='form-text help-block text-danger'>" . ($this->errors[$field["name"]] ?? "") . "</small>";
            }
            $form .= "</div>";
        }

        $form .= "<button type='submit' class='btn btn-primary'>$this->submit</button>";
        $form .= "</form>";
        return $form;
    }

    /**
     * Get the form's data
     *
     * @return array The form's data
     */
    public function getData(): array
    {
        return $this->data;
    }


}
