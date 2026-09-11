<?php

namespace App\Forms\Components;

use App\Models\HR\Project;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;

class ProjectCollaborationPlugin implements RichContentPlugin
{
    public function getTipTapPhpExtensions(): array
    {
        return [];
    }

    public function getTipTapJsExtensions(): array
    {
        return [asset('build/project-collaboration.js') . '?v=' . filemtime(public_path('build/project-collaboration.js'))];
    }

    public function getEditorTools(): array
    {
        return [];
    }

    public function getEditorActions(): array
    {
        return [];
    }

    /** @return array{document: string, token: string, user: array{name: string}} */
    public static function configuration(int $projectId): array
    {
        abort_unless(auth()->user()?->canAccessPanel(filament()->getDefaultPanel()) === true, 403);

        $version = (int) Project::query()->whereKey($projectId)->value('description_version');
        $payload = base64_encode(json_encode([
            'projectId' => $projectId,
            'userId' => auth()->id(),
            'version' => $version,
            'expires' => now()->addDay()->timestamp,
        ], JSON_THROW_ON_ERROR));

        return [
            'document' => 'project.' . $projectId . ($version ? '.v' . $version : ''),
            'token' => $payload . '.' . hash_hmac('sha256', $payload, config('collaboration.key')),
            'user' => ['name' => auth()->user()->name],
        ];
    }
}
