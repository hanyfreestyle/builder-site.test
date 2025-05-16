<?php

namespace App\Services\Builder;

use App\Models\Builder\Template;
use App\Models\Builder\Page;
use Illuminate\Support\Facades\DB;

class TemplateService
{
    /**
     * تحويل جميع الصفحات من قالب معين إلى استخدام القالب الافتراضي
     *
     * @param Template|int $fromTemplate القالب المصدر المراد تحويل صفحاته
     * @param boolean $useDefaultFlag ما إذا كان سيتم استخدام علامة القالب الافتراضي أو تعيين القالب الافتراضي مباشرة
     * @return int عدد الصفحات التي تم تحويلها
     */
    public static function migrateTemplatePagesToDefault($fromTemplate, bool $useDefaultFlag = true): int
    {
        $templateId = $fromTemplate instanceof Template ? $fromTemplate->id : $fromTemplate;
        $defaultTemplate = Template::getDefault();
        
        if (!$defaultTemplate) {
            throw new \Exception('No default template found');
        }
        
        // استخدام معاملة قاعدة البيانات للتحويل
        return DB::transaction(function() use ($templateId, $defaultTemplate, $useDefaultFlag) {
            $pages = Page::where('template_id', $templateId)->get();
            $count = 0;
            
            foreach ($pages as $page) {
                if ($useDefaultFlag) {
                    // استخدام علامة "استخدام القالب الافتراضي"
                    $page->use_default_template = true;
                    // الاحتفاظ بالقالب الحالي كمرجع تاريخي
                    $page->save();
                } else {
                    // تعيين القالب الافتراضي مباشرة
                    $page->template_id = $defaultTemplate->id;
                    $page->use_default_template = false;
                    $page->save();
                }
                $count++;
            }
            
            return $count;
        });
    }
    
    /**
     * تحويل جميع الصفحات لاستخدام القالب الافتراضي
     *
     * @param boolean $useDefaultFlag ما إذا كان سيتم استخدام علامة القالب الافتراضي أو تعيين القالب الافتراضي مباشرة
     * @return int عدد الصفحات التي تم تحويلها
     */
    public static function migrateAllPagesToDefault(bool $useDefaultFlag = true): int
    {
        $defaultTemplate = Template::getDefault();
        
        if (!$defaultTemplate) {
            throw new \Exception('No default template found');
        }
        
        // استخدام معاملة قاعدة البيانات للتحويل
        return DB::transaction(function() use ($defaultTemplate, $useDefaultFlag) {
            $pages = Page::where('template_id', '!=', $defaultTemplate->id)
                        ->where('use_default_template', false)
                        ->get();
            $count = 0;
            
            foreach ($pages as $page) {
                if ($useDefaultFlag) {
                    // استخدام علامة "استخدام القالب الافتراضي"
                    $page->use_default_template = true;
                    // الاحتفاظ بالقالب الحالي كمرجع تاريخي
                    $page->save();
                } else {
                    // تعيين القالب الافتراضي مباشرة
                    $page->template_id = $defaultTemplate->id;
                    $page->use_default_template = false;
                    $page->save();
                }
                $count++;
            }
            
            return $count;
        });
    }
}