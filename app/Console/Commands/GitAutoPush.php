<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class GitAutoPush extends Command {

    protected $signature = 'app:git-add';
    protected $description = 'Add, commit, and push git changes automatically';

    public function handle() {
        $commands = [
            ['git', 'add', '.'],
            ['git', 'commit', '-m', 'Update'],
            ['git', 'push'],
        ];

        foreach ($commands as $cmd) {
            $process = new Process($cmd);
            $process->run();

            // عرض المخرجات
            $this->line($process->getOutput());

            // عرض الأخطاء لو حصلت
            if (!$process->isSuccessful()) {
                $this->error($process->getErrorOutput());
                return Command::FAILURE;
            }
        }

        // مسح الشاشة بعد النجاح (لو على ويندوز)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cls');
        } else {
            system('clear');
        }

        return Command::SUCCESS;
    }
}
