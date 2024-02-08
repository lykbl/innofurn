<?php

declare(strict_types=1);

namespace App\FieldTypes;

use Lunar\Base\FieldType;
use Lunar\Exceptions\FieldTypeException;

class ColorFieldType implements FieldType
{
    protected ?string $value;

    public function __construct($value = '')
    {
        $this->setValue($value);
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        if (!is_string($value)) {
            throw new FieldTypeException(self::class.' value must be a string.');
        }

        $this->value = $value;
    }

    public function getLabel(): string
    {
        return __('adminhub::fieldtypes.color-picker.type');
    }

    public function getConfig(): array
    {
        return [
            'options' => [
                'label' => 'string',
                'color' => 'string',
            ],
        ];
    }

    public function getSettingsView(): string
    {
        return 'adminhub::field-types.color-picker.settings';
    }

    public function getView(): string
    {
        return 'adminhub::field-types.color-picker.view';
    }
}
