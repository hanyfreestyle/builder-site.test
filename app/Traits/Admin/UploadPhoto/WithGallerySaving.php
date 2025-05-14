<?php

namespace App\Traits\Admin\UploadPhoto;

trait WithGallerySaving {

    protected ?string $galleryRelation = 'photos';

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function setRelation(string $relation): static {
        $this->galleryRelation = $relation;
        return $this; // عشان تقدر تعمل chaining
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function afterSaveGallery() {
        if (!$this->galleryRelation) {
            $this->galleryRelation = 'photos';
        }

        $data = $this->form->getState();
        $record = $this->record;

        if (!empty($data['gallery'])) {
            foreach ($data['gallery'] as $image) {
                $record->{$this->galleryRelation}()->create([
                    'photo' => $image,
                    'photo_thumbnail' => str_replace('.webp', '_thumb.webp', $image),
                    'is_active' => true,
                    'position' => 0,
                ]);
            }

            $this->form->fill([
                'gallery' => [],
            ]);

            $this->redirect($this->getResource()::getUrl('edit', ['record' => $record->getKey()]));
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function afterCreateGallery() {
        if (!$this->galleryRelation) {
            $this->galleryRelation = 'photos';
        }

        $data = $this->form->getState();
        $record = $this->record;

        if (!empty($data['photo'])) {
            $record->photo_thumbnail = str_replace('.webp', '_thumb.webp', $data['photo']);
            $record->save();
        }

        if (!empty($data['gallery'])) {
            foreach ($data['gallery'] as $image) {
                $record->{$this->galleryRelation}()->create([
                    'photo' => $image,
                    'photo_thumbnail' => str_replace('.webp', '_thumb.webp', $image),
                    'is_active' => true,
                    'position' => 0,
                ]);
            }

            $this->form->fill([
                'gallery' => [],
            ]);
            // 🚀 الحل السحري - تحديث علاقة الصور
            $this->redirect($this->getResource()::getUrl('edit', ['record' => $record->getKey()]));
        }
    }

}
