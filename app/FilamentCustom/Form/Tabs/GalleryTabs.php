<?php

namespace App\FilamentCustom\Form\Tabs;


use App\FilamentCustom\UploadFile\WebpUploadWithFilter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Support\HtmlString;

class GalleryTabs {
    protected string $uploadDirectory = 'uploads';
    protected int $galleryFilter = 0;

    public static function make(): static {
        return new static();
    }

    public function setUploadDirectory(string $uploadDirectory): static {
        $this->uploadDirectory = $uploadDirectory;
        return $this;
    }

    public function setGalleryFilter(int $galleryFilter): static {
        $this->galleryFilter = $galleryFilter;
        return $this;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getColumns($model): array {
        return [
            Tab::make(__('default/lang.construct.gallery_tab'))
                ->visible(fn($record) => filled($record))
                ->icon('heroicon-s-photo')
                ->schema([
                    Group::make()->schema([
                        Placeholder::make("")
                            ->content(function ($record) use ($model) {
                                return new HtmlString(view('components.admin.media.media-manager-list', [
                                    'record' => $record,
                                    'modelName' => $model,
                                ])->render());
                            }),
                    ])->columnSpan(2),
                    Group::make()->schema([
                        Section::make(__('default/lang.construct.gallery_file'))->schema([
                            ...WebpUploadWithFilter::make()
                                ->setFileName('gallery')
                                ->setMultipleFiles(true)
                                ->setFilterId($this->galleryFilter)
                                ->setUploadDirectory($this->uploadDirectory)
                                ->setRequiredUpload(false)
//                                ->setRenameTo('Hany Mohamed Darwish')
                                ->setRenameFromDb('slug')
                                ->getColumns(),
                        ]),
                    ])->columnSpan(1),
                ])->columns(3)
        ];
    }

}
