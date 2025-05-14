<?php

namespace App\Traits\Admin\Model;

trait ForceSaveWithTranslations {
    public function forceSaveWithTranslations(array $attributes = [], array $translated = []): bool {
        // ملأ البيانات العادية
        if (!empty($attributes)) {
            $this->fill($attributes);
        }

        // ملأ الترجمة لو موجودة
        foreach ($translated as $key => $translations) {
            foreach ($translations as $locale => $value) {
                $this->setTranslation($key, $locale, $value);
            }
        }

        // حفظ البيانات بدون إطلاق Events مثل saved() اللي بتعمل مشاكل
        $result = $this->saveQuietly();

        // حفظ الترجمة يدويًا بعد الحفظ الحقيقي
        if (method_exists($this, 'saveTranslations')) {
            $this->saveTranslations();
        }

        return $result;
    }
}
