<?php

namespace App;

use Smarty\Smarty;

class Template
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->smarty = new Smarty();

        $this->smarty->setTemplateDir(
            __DIR__ . '/../templates'
        );

        $this->smarty->setCompileDir(
            __DIR__ . '/../var/compile'
        );

        $this->smarty->setCacheDir(
            __DIR__ . '/../var/cache'
        );

        $this->smarty->setEscapeHtml(true);
    }

    public function render(
        string $template,
        array $data = []
    ): string {
        $this->smarty->assign($data);

        return $this->smarty->fetch($template);
    }
}
