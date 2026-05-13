<?php

function scanDirectoryTree($dir, $depth = 0)
{
    $result = [];

    // Safety limit (prevents accidental infinite recursion)
    if ($depth > 6) {
        return $result;
    }

    if (!is_dir($dir)) {
        return $result;
    }

    $items = scandir($dir);

    foreach ($items as $item) {

        if ($item === '.' || $item === '..') {
            continue;
        }

        $path = $dir . DIRECTORY_SEPARATOR . $item;

        // Skip unreadable directories
        if (!is_readable($path)) {
            continue;
        }

        if (is_dir($path)) {

            $result[] = [
                'text' => $item,
                'path' => $path,
                'children' => scanDirectoryTree($path, $depth + 1)
            ];
        }
    }

    return $result;
}

function getSavedSelections($pdo)
{
    $stmt = $pdo->query("SELECT path FROM excluded_paths WHERE selected = 1");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function formatForJsTree($nodes, $saved = [])
{
    $result = [];

    foreach ($nodes as $node) {

        $isChecked = in_array($node['path'], $saved);

        $item = [
            'text' => $node['text'],
            'id' => $node['path'],
            'state' => [
                'selected' => $isChecked
            ]
        ];

        if (!empty($node['children'])) {
            $item['children'] = formatForJsTree($node['children'], $saved);
        }

        $result[] = $item;
    }

    return $result;
}


