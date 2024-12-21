<?php

namespace App\Models;

class Color {
    public $color;

    public function __construct($color) {
        $this->color = $color; // تعيين قيمة الخاصية
    }
}

$colorObject = new Color('red'); // هنا أنشأنا كائنًا
echo $colorObject->color; // هذا يعمل بشكل صحيح