<?php

namespace App\Http\Controllers;

use App\Models\Color; // استيراد الكلاس

class ColorController extends Controller {
    public function show() {
        $colorObject = new Color('red'); // إنشاء كائن جديد
        return $colorObject->color; // طباعة الخاصية color
    }
}