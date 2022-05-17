<?php

namespace App\ImportVideo;

class ImportVideoFlub extends ImportVideo
{
    protected $format = 'yaml';

    public function parse(): bool
    {
        if (!count($this->rows)) return false;

        $parsedRows = [];
        foreach ($this->rows as $row) {
            $tags = isset($row['labels']) ? explode(',', $row['labels']) : [];
            foreach($tags as &$tag) $tag = trim($tag);
            unset($tag);
            $parsedRows[] = [
                'name' => $row['name'] ?? '',
                'url'  => $row['url'] ?? '',
                'tags' => implode(',', $tags),
            ];
        }
        $this->rows = $parsedRows;
        return true;
    }

    public function save()
    {
        return true;
    }
}