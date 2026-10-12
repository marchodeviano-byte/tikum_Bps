<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class KehadiranImport implements ToArray
{
    public function array(array $array)
    {
        /*
         * Data Excel hanya dibaca menjadi array.
         *
         * Proses parsing template BPS dilakukan
         * di KehadiranController.
         */
    }
}
