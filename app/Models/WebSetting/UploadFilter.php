<?php

namespace App\Models\WebSetting;

use App\Traits\Admin\Model\WithModelEventsCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;


class UploadFilter extends Model {
    use SoftDeletes;
    use WithModelEventsCache;


    protected $table = "config_upload_filter";
    protected static string $cashKey = 'cash_config_upload_filter';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'type', 'convert_state', 'quality_val', 'width', 'height', 'crop_aspect_ratio', 'canvas_back', 'greyscale', 'flip_state', 'flip_v', 'blur', 'blur_size', 'pixelate', 'pixelate_size', 'text_state', 'text_print', 'font_size', 'font_path', 'font_color', 'font_opacity', 'text_position', 'watermark_state', 'watermark_img', 'watermark_position', 'state', 'is_notes', 'notes', 'deleted_at'];

    protected $casts = [
        'notes' => 'array',
    ];

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    protected static function booted() {
        static::bootWithModelEventsCache();
    }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function sizes(): HasMany {
        return $this->hasMany(UploadFilterSize::class, 'filter_id');
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function getLocalizedNote(): ?string {
        $lang = app()->getLocale();
        // تأكد إن notes مصفوفة
        if (!is_array($this->notes) || empty($this->notes)) {
            return null;
        }
        $width = $this->width ?? null;
        $height = $this->height ?? null;
        return str_replace(['[w]', '[h]'], [$width, $height], $this->notes[$lang]) ?? null;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public static function getUploadFilterCashList($IsCash = true) {
        $cashKey = self::$cashKey;
        if ($IsCash) {
            return Cache::remember($cashKey . app()->getLocale(), 3600, function () {
                return UploadFilter::query()->get();
            });
        } else {
            return UploadFilter::query()->withCount('sizes')->with('sizes')->get();
        }
    }
}
