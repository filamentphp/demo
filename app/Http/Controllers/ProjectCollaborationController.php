<?php

namespace App\Http\Controllers;

use App\Models\HR\Project;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectCollaborationController extends Controller
{
    public function show(Request $request, Project $project): JsonResponse
    {
        abort_unless(hash_equals(config('collaboration.key'), $request->bearerToken() ?? ''), 403);

        return response()->json([
            'state' => $project->description_state,
            'content' => RichContentRenderer::make($project->description)->getEditor()->getDocument(),
        ]);
    }
}
