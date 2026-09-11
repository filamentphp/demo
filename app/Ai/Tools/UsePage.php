<?php

namespace App\Ai\Tools;

use App\Ai\PageInteraction;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Validation\ValidationException;
use JsonException;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;

class UsePage implements Tool
{
    public function __construct(public PageInteraction $page) {}

    public function description(): string
    {
        return 'Inspect and interact with the current Filament resource page. input is a JSON object: inspect/read_history/read_chat/read_revisions/save_form: {}; update_form: {"data.name":"New name"} using exact editable paths; propose_revision (Project view/edit only): {"values":{"name":"Name","budget":100,"end_date":"2026-12-31","description":"..."},"tasks":{"123":{"title":"Task","status":"in_progress","due_date":"2026-10-01"}},"reason":"Why"}; configure_table: {"search":"term","sort":"name","direction":"asc","filters":{}}; run_action: {"name":"action_name","record_key":"123"}. Project update_form/save_form are forbidden: use propose_revision. Never approve or apply a revision; human review is required.';
    }

    public function handle(Request $request): string
    {
        try {
            $input = json_decode($request['input'], true, 32, JSON_THROW_ON_ERROR);
            if (! is_array($input)) {
                return 'Error: input must be a JSON object.';
            }

            return json_encode($this->page->operate($request['operation'], $input), JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
        } catch (ValidationException $exception) {
            return json_encode(['errors' => $exception->errors()], JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return 'Error: invalid JSON input.';
        }
    }

    /** @return array<string, Type> */
    public function schema(JsonSchema $schema): array
    {
        return [
            'operation' => $schema->string()->enum(['inspect', 'update_form', 'configure_table', 'run_action', 'save_form', 'propose_revision', 'read_revisions', 'read_history', 'read_chat'])->required(),
            'input' => $schema->string()->description('JSON object containing operation arguments. Use {} for read operations.')->required(),
        ];
    }
}
