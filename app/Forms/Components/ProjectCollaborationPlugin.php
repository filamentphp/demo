<?php

namespace App\Forms\Components;

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

        $payload = base64_encode(json_encode([
            'projectId' => $projectId,
            'userId' => auth()->id(),
            'expires' => now()->addDay()->timestamp,
        ], JSON_THROW_ON_ERROR));

        return [
            'document' => 'project.' . $projectId,
            'token' => $payload . '.' . hash_hmac('sha256', $payload, config('collaboration.key')),
            'user' => ['name' => auth()->user()->name],
        ];
    }
}
