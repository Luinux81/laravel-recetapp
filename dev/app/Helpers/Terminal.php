<?php

namespace App\Helpers;

class Terminal{

    public static function consoleProgressBar($done, $total) {
        $perc = floor(($done / $total) * 100);
        $left = 100 - $perc;
        $write = sprintf("\033[0G\033[2K[%'={$perc}s>%-{$left}s] - $perc%% - $done/$total", "", "");
        fwrite(STDERR, $write);
    }

    public static function consoleFixedText($texto){
        $write = sprintf("\033[0G\033[2K $texto ", "", "");
        fwrite(STDERR, $write);
    }
}