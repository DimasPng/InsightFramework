<?php

namespace App\Console\Commands;

use App\Core\Command;

class MakeModelCommand extends Command
{
    protected string $name = 'make:model';
    protected string $description = 'Create a new model class';

    public function handle(array $args): void
    {
        $modelName = $args[0] ?? null;

        if (!$modelName) {
            echo "Error: Model name is required.\n";
            return;
        }

        $filePath = __DIR__ . '/../../Models/' . ucfirst($modelName) . '.php';

        if (file_exists($filePath)) {
            echo "Error: Model {$modelName} already exists.\n";
            return;
        }

        $content = $this->getModelTemplate($modelName);

        file_put_contents($filePath, $content);
        echo "Model {$modelName} created successfully.\n";
    }

    protected function getModelTemplate(string $modelName): string
    {
        return <<<PHP
                <?php

                namespace App\Models;

                use App\Core\Model;

                class {$modelName} extends Model
                {
                
                }
                PHP;
    }
}
