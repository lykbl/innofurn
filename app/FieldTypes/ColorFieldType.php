<?php

declare(strict_types=1);

namespace App\FieldTypes;

use Lunar\Base\FieldType;
use Lunar\Exceptions\FieldTypeException;

class ColorFieldType implements FieldType
{
    public function __construct(protected ?array $value = null)
    {
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        if (!is_array($value)) {
            throw new FieldTypeException(self::class.' value must be an array.');
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
