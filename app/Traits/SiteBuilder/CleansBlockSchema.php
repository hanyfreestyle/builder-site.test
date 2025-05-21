<?php

namespace App\Traits\SiteBuilder;

trait CleansBlockSchema {
    protected function cleanSchema(array $schema): array {
        foreach ($schema as &$field) {
            $type = is_string($field['type'] ?? null) ? $field['type'] : null;
            if ($type !== 'image') {
                unset($field['config']);
            }
            if (!empty($field['config']['fields'])) {
                $field['config']['fields'] = $this->cleanSchema($field['config']['fields']);
            }
        }
        unset($field);
        return $schema;
    }

    protected function applySchemaCleaning(array $data): array {
        $data['schema'] = $this->cleanSchema($data['schema'] ?? []);
        return $data;
    }
}
