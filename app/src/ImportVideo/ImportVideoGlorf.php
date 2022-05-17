<?php

namespace App\ImportVideo;

class ImportVideoGlorf extends ImportVideo
{
    protected $format = 'json';

    public function parse() : bool
    {
        if(!count($this->rows) || !isset($this->rows['videos'])) return false;

        $parsedRows = [];
        foreach ($this->rows['videos'] as $row) {
            $parsedRows[] = [
                'name' => $row['title'] ?? '',
                'url' => $row['url'] ?? '',
                'tags' => isset($row['tags']) ? implode(',',$row['tags']) : '',
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