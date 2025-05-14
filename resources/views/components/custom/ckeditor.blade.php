<div
    x-data="{
        editor: null,
        initEditor() {
            if (this.editor) {
                this.editor.destroy();
            }

            const config = {
                language: '{{ $getLocale() }}',
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
                // ... إعدادات أخرى
            };

            ClassicEditor
                .create(this.$refs.editor, config)
                .then(editor => {
                    this.editor = editor;
                    editor.model.document.on('change:data', () => {
                        this.$dispatch('input', editor.getData());
                    });
                })
                .catch(console.error);
        }
    }"
    x-init="
        // تهيئة المحرر عند التحميل الأولي
        initEditor();

        // إعادة التهيئة عند تغيير التبويب
        Livewire.hook('element.updated', (el, component) => {
            if (el.getAttribute('wire:id') === $wire.__instance.id) {
                initEditor();
            }
        });
    "
    wire:ignore.self
    style="position: relative;"
>
    <div x-ref="editor" id="ckeditor-{{ $getLocale() }}">{!! $getState() !!}</div>
</div>

@pushOnce('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable {
        min-height: 200px;
        background: white !important;
        color: #1f2937 !important;
    }
    .ck.ck-toolbar {
        background: #f3f4f6 !important;
    }
</style>
@endPushOnce
