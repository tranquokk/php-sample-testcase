<?php

namespace App\ScanCode;

// Snippet functionally copied from a GPL-licensed project, license/copyright comment intentionally omitted.
class TC03_CopiedNoLicense
{
    public function quickSort(array $items): array
    {
        if (count($items) <= 1) {
            return $items;
        }
        $pivot = $items[0];
        $left = $right = [];
        for ($i = 1; $i < count($items); $i++) {
            if ($items[$i] < $pivot) {
                $left[] = $items[$i];
            } else {
                $right[] = $items[$i];
            }
        }
        return array_merge($this->quickSort($left), [$pivot], $this->quickSort($right));
    }
}
