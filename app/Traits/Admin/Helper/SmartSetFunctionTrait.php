<?php

namespace App\Traits\Admin\Helper;

trait SmartSetFunctionTrait {
    protected bool $setDes = true;
    protected bool $setDataRequired = true;
    protected bool $setSeoRequired = false;
    protected bool $setEditor = false;
    protected bool $setSeo = true;
    protected string $setNameLabel = '';
    protected string $setDesLabel= '';
    protected bool $setMarkdown  = false;
    protected bool $setRichEditor  = false;
    protected int $setTextAreaRow  = 6;


    public function __construct() {
        $this->setNameLabel = __('default/lang.construct.name');
        $this->setDesLabel = __('default/lang.construct.description');
    }


    public function setDes(bool $setDes): static {
        $this->setDes = $setDes;
        return $this;
    }
    public function setMarkdown(bool $setMarkdown): static {
        $this->setMarkdown = $setMarkdown;
        return $this;
    }
    public function setRichEditor(bool $setRichEditor): static {
        $this->setRichEditor = $setRichEditor;
        return $this;
    }


    public function setEditor(bool $setEditor): static {
        $this->setEditor = $setEditor;
        return $this;
    }

    public function setSeo(bool $setSeo): static {
        $this->setSeo = $setSeo;
        return $this;
    }

    public function setSeoRequired(bool $setSeoRequired): static {
        $this->setSeoRequired = $setSeoRequired;
        return $this;
    }

    public function setDataRequired(bool $setDataRequired): static {
        $this->setDataRequired = $setDataRequired;
        return $this;
    }

    public function setNameLabel(?string $label = null): static {
        $this->setNameLabel = $label ?? __('default/lang.columns.name');
        return $this;
    }

    public function setDesLabel(?string $label = null): static {
        $this->setDesLabel = $label ?? __('default/lang.columns.description');
        return $this;
    }

    public function setTextAreaRow(?string $setTextAreaRow = null): static {
        $this->setTextAreaRow = $setTextAreaRow ?? 6;
        return $this;
    }

}
