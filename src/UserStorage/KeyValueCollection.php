<?php

namespace EasyByteDance\MiniApp\UserStorage;

use EasyByteDance\MiniApp\Exceptions\InvalidArgumentException;
use Foris\Easy\Support\Collection;

/**
 * Class KeyValueCollection
 */
class KeyValueCollection extends Collection
{
    /**
     * KeyValueCollection constructor.
     *
     * @param array $items
     * @throws InvalidArgumentException
     */
    public function __construct(array $items = [])
    {
        parent::__construct([]);
        $this->addItems($items);
    }

    /**
     * Add key-value item
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function addItem($key, $value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->items[] = ['key' => $key, 'value' => $value];

        return $this;
    }

    /**
     * Add key-value items
     *
     * @param array $items
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addItems(array $items)
    {
        foreach ($items as $item) {
            if (!isset($item['key']) || !isset($item['value'])) {
                throw new InvalidArgumentException('Key-value list item must contain "key" and "value"!');
            }

            $this->addItem($item['key'], $item['value']);
        }

        return $this;
    }
}
