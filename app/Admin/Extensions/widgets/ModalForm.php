<?php


namespace App\Admin\Extensions\widgets;


use Encore\Admin\Widgets\Form;

class ModalForm
{
    /**
     * @var Form
     */
    private $form;
    /**
     * @var string
     */
    private $buttonName;
    /**
     * @var string
     */
    private $modalTitle;

    /**
     * ModalForm constructor.
     * @param array $buttonName
     * @param string $modalTitle
     * @param Form $form
     */
    public function __construct(array $buttonName, string $modalTitle, Form $form)
    {
        $this->buttonName = $buttonName;
        $this->modalTitle = $modalTitle;
        $this->form = $form;
    }

    public function getButtonName()
    {
        return $this->buttonName;
    }

    public function getTitle()
    {
        return $this->modalTitle;
    }

    public function render()
    {
        return $this->form->render();
    }
}