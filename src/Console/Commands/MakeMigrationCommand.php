<?php

namespace App\Console\Commands;

use App\Core\Command;

class MakeMigrationCommand extends Command
{
    protected string $name = 'make:migration';
    protected string $description = 'Create a new migration file';

    public function handle(array $args): void
    {
        $migrationName = $args[0] ?? null;

        if (!$migrationName) {
            echo "Error: Migration name is required.\n";
            return;
        }

        $timestamp = date('Y_m_d_His');
        $className = $this->convertToClassname($migrationName);
        $tableName = $this->extractTableName($migrationName);
        $fileName = "{$timestamp}_{$migrationName}.php";
        $migrationPath = __DIR__ . '/../../Database/Migrations/' . $fileName;

        $template = $this->getMigrationTemplate($tableName);

        if (!file_exists(dirname($migrationPath))) {
            mkdir(dirname($migrationPath), 0755, true);
        }

        file_put_contents($migrationPath, $template);

        echo "Migration created: {$fileName}\n";
    }

    protected function convertToClassname(string $name): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
    }

    protected function extractTableName(string $migrationName): string
    {
        $tableName = preg_replace('/^create_|_table$/', '', $migrationName);

        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $tableName));
    }


    protected function getMigrationTemplate(string $tableName): string
    {
        return <<<PHP
<?php
            
namespace App\Database\Migrations;

use App\Database\Migration;            
use App\Database\Blueprint;
use App\Database\Schema;
            
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('$tableName', function (Blueprint \$table) {
            \$table->id();
            \$table->string('column_name');
            \$table->timestamps();
        });
    }
            
    public function down(): void
    {
        Schema::dropIfExists('$tableName');
    }
};
PHP;
    }
}
