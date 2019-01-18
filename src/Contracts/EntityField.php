<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 21.07.18
 * Time: 02:06
 */

namespace ARudkovskiy\Admin\Contracts;

use Illuminate\Http\Request;

abstract class EntityField
{

    /** @var string */
    protected $template;

    /** @var array */
    protected $options = [];

    protected $value;

    /** @var Entity */
    protected $entity;

    /** @var string|\Closure  */
    protected $view = null;

    /** @var string */
    protected $label;

    /** @var string */
    protected $name;

    /** @var bool  */
    protected $shouldShowInIndexTable = false;

    /** @var int  */
    protected $orderInIndexTable = -1;

    protected $width = null;

    protected $isUpdatingManually = false;

    /** @var string */
    protected $uniqueIdentifier = null;

    /** @var bool  */
    protected $shouldIgnoreOnUpdate = false;

    /** @var bool */
    protected $handleAfterSave = false;

    public function __construct()
    {
        $this->generateUniqueIdentifier();
    }

    public function renderEditable() {
        if ($this->template === null) {
            return null;
        }

        return view($this->template, $this->getOptions());
    }

    public function render()
    {
        if ($this->view instanceof \Closure || gettype($this->view) === 'array') {
            return call_user_func($this->view, $this->getValue(), $this->getEntity()->getObject(), $this);
        }

        return view($this->view, [
            'value' => $this->getValue(),
            'record' => $this->getEntity()->getObject(),
            'field' => $this
        ]);
    }

    public function process($value) {
        $this->value = $value;
    }

    public function handleRequest(Request $request, $entityObject)
    {
        $updatedValue = $request->get($this->name);
        $this->value = $updatedValue;
    }

    public function generateLabelFrom(string $text)
    {
        $text = str_replace('.', ' ', $text);
        $text = ucfirst($text);
        return $text;
    }

    public static function create(string $name, string $label = null, $view = null) {
        $instance = new static;
        $instance->setName($name);
        $instance->setLabel($label);
        $instance->setView($view);
        return $instance;
    }

    public function getDefaultOptions(): array
    {
        return [
            'location' => 'content',
            'name' => $this->getName(),
            'value' => $this->value,
            'label' => $this->getLabel() ?? $this->generateLabelFrom($this->getName()),
            'attributes' => [ /* empty */ ]
        ];
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getDefaultValue()
    {
        return null;
    }

    /**
     * @return bool
     */
    public function isUpdatingManually(): bool
    {
        return $this->isUpdatingManually;
    }

    /**
     * @param bool $isUpdatingManually
     */
    public function setIsUpdatingManually(bool $isUpdatingManually)
    {
        $this->isUpdatingManually = $isUpdatingManually;
    }

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     */
    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return \Closure|string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param \Closure|array|string $view
     * @return EntityField
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label ?? $this->getTranslatedLabel();
    }

    /**
     * @param string $label
     * @return EntityField
     */
    public function setLabel(string $label = null)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name ?? get_class($this);
    }

    /**
     * @param string $name
     * @return EntityField
     */
    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function showInIndexTable() {
        $this->shouldShowInIndexTable = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShouldShowInIndexTable(): bool
    {
        return $this->shouldShowInIndexTable;
    }

    /**
     * @return int
     */
    public function getOrderInIndexTable(): int
    {
        return $this->orderInIndexTable;
    }

    /**
     * @param int $orderInIndexTable
     * @return self
     */
    public function setOrderInIndexTable(int $orderInIndexTable)
    {
        $this->orderInIndexTable = $orderInIndexTable;
        return $this;
    }

    /**
     * @return null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param null $width
     * @return EntityField
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function getTotalWidth()
    {
        return !empty($this->width) ? $this->getWidth() : null;
    }

    public function setOptions(array $options) {
        $this->options = $options;
        return $this;
    }

    public function getOptions() {
        return array_merge($this->getDefaultOptions() ?? [], $this->options);
    }

    public function getOption(string $option) {
        return array_get($this->getOptions(), $option);
    }

    /**
     * @return bool
     */
    public function isShouldIgnoreOnUpdate(): bool
    {
        return $this->shouldIgnoreOnUpdate;
    }

    /**
     * @param bool $shouldIgnoreOnUpdate
     * @return EntityField
     */
    public function setShouldIgnoreOnUpdate(bool $shouldIgnoreOnUpdate)
    {
        $this->shouldIgnoreOnUpdate = $shouldIgnoreOnUpdate;
        return $this;
    }

    public function getManuallyUpdatableField()
    {
        return null;
    }

    private function getTranslatedLabel() {
        if ($this->entity === null) {
            return null;
        }

        return $this->entity->translate("field.{$this->name}");
    }

    public function getUniqueIdentifier() {
        return $this->uniqueIdentifier;
    }

    /**
     * @return bool
     */
    public function isHandleAfterSave(): bool
    {
        return $this->handleAfterSave;
    }

    /**
     * @param bool $handleAfterSave
     * @return EntityField
     */
    public function setHandleAfterSave(bool $handleAfterSave): EntityField
    {
        $this->handleAfterSave = $handleAfterSave;
        return $this;
    }

    protected function generateUniqueIdentifier() {

    }

}