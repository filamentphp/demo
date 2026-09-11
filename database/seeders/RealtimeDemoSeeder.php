<?php

namespace Database\Seeders;

use App\Models\HR\Department;
use App\Models\HR\Employee;
use App\Models\HR\Project;
use App\Models\PageMessage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RealtimeDemoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Project::withoutEvents(fn () => (new Project)->getConnection()->transaction(function (): void {
            $department = Department::query()->firstOrCreate(['slug' => 'northstar-product'], [
                'name' => 'Northstar Product', 'description' => 'The team building a calmer customer workspace.',
                'budget' => 500000, 'headcount_limit' => 12, 'color' => '#6366f1',
            ]);
            $users = [];
            $employees = [];
            foreach ([['maya', 'Maya Chen', 'Product lead'], ['leo', 'Leo Martinez', 'Engineering lead'], ['ava', 'Ava Patel', 'Product designer'], ['sam', 'Sam Okafor', 'Customer success lead']] as [$key, $name, $role]) {
                $email = $key . '@northstar.example';
                $users[$key] = User::query()->firstOrCreate(['email' => $email], [
                    'name' => $name, 'password' => Hash::make('demo.Filament@2021!'), 'email_verified_at' => now(),
                ]);
                $employees[$key] = Employee::withTrashed()->firstOrCreate(['email' => $email], [
                    'name' => $name, 'job_title' => $role, 'department_id' => $department->id,
                    'hire_date' => now()->subYears(2)->toDateString(), 'employment_type' => 'full_time',
                    'team_color' => '#6366f1',
                ]);
            }

            foreach ([
                ['portal', 'Customer portal redesign', 'on_hold', 'high', 60000, 38000, 14, 'Waiting for security approval of customer document sharing. Ava owns the designs; Leo owns the security review.', ['Approve document-sharing security review', 'Build the customer document workspace', 'Run customer acceptance sessions']],
                ['launch', 'Northstar launch campaign', 'active', 'high', 45000, 18000, 21, 'Launch the new workspace to 200 early-access customers. Messaging and the landing page are approved.', ['Publish the launch landing page', 'Schedule the customer announcement', 'Review early-access feedback']],
                ['migration', 'Customer data migration', 'active', 'critical', 35000, 29000, -3, 'The import is overdue because duplicate account records need reconciliation. Leo owns the final cutover.', ['Reconcile duplicate customer accounts', 'Rehearse the production cutover', 'Verify customer record counts']],
                ['billing', 'Self-service billing', 'active', 'high', 50000, 47500, 10, 'The rollout is close to its budget limit. Ship invoice downloads first; defer subscription upgrades until the next cycle.', ['Verify invoice downloads', 'Review the remaining vendor costs', 'Enable billing for pilot customers']],
                ['onboarding', 'Customer onboarding playbook', 'completed', 'medium', 12000, 10500, -7, 'The welcome checklist and support handoff are live. Sam has trained the customer success team.', ['Publish the welcome checklist', 'Train customer success on the handoff', 'Review the first five onboarding sessions']],
                ['research', 'Mobile workspace discovery', 'planning', 'low', 18000, 0, 45, 'Explore how account managers use the workspace away from their desks. Research has not started yet.', []],
            ] as [$key, $name, $status, $priority, $budget, $spent, $due, $description, $tasks]) {
                if (Project::withTrashed()->where('slug', 'northstar-' . $key)->exists()) {
                    continue;
                }
                $created = now()->startOfDay()->subDays(28)->setHour(9);
                $project = Project::query()->create([
                    'slug' => 'northstar-' . $key, 'name' => $name, 'department_id' => $department->id,
                    'description' => '<p>' . $description . '</p>', 'status' => $status, 'priority' => $priority,
                    'budget' => $budget, 'spent' => $spent, 'estimated_hours' => 240,
                    'actual_hours' => $key === 'research' ? 0 : ($key === 'onboarding' ? 220 : 140),
                    'color' => '#6366f1', 'start_date' => $created, 'end_date' => today()->addDays($due),
                    'plan' => [['type' => 'milestone', 'data' => ['title' => $key === 'research' ? 'Share discovery findings' : 'Delivery review', 'target_date' => today()->addDays($due)->toDateString(), 'description' => $description]]],
                    'created_at' => $created, 'updated_at' => now()->subDays(2),
                ]);
                if ($key === 'research') {
                    continue;
                }
                $project->activities()->create([
                    'user_id' => $users['maya']->id, 'actor_name' => $users['maya']->name, 'interface' => 'panel',
                    'event' => 'project_created', 'changes' => [], 'created_at' => $created, 'updated_at' => $created,
                ]);
                foreach ($tasks as $index => $title) {
                    $owner = $index === 0 ? 'leo' : ($index === 1 ? 'ava' : 'sam');
                    $completed = $key === 'onboarding' || ($key === 'launch' && $index === 0);
                    $task = $project->tasks()->create([
                        'title' => $title, 'description' => $key === 'portal' && $index > 0 ? 'Depends on approval of the document-sharing security review.' : 'Share the outcome with the team in the project conversation.',
                        'assigned_to' => $employees[$owner]->id, 'status' => $completed ? 'completed' : ($index === 0 ? 'in_progress' : 'todo'),
                        'priority' => $priority, 'due_date' => today()->addDays($due - 4 + $index),
                        'estimated_hours' => 24, 'actual_hours' => $completed ? 22 : 8,
                        'completed_at' => $completed ? now()->subDays(8) : null, 'sort' => $index,
                        'labels' => [$key === 'portal' ? 'security' : 'launch'], 'created_at' => $created->copy()->addDays(1), 'updated_at' => now()->subDays(2),
                    ]);
                    $project->activities()->create([
                        'user_id' => $users[$owner]->id, 'actor_name' => $users[$owner]->name, 'interface' => 'panel',
                        'event' => 'task_created', 'subject' => $task->title, 'changes' => [],
                        'created_at' => $task->created_at, 'updated_at' => $task->created_at,
                    ]);
                }
                $changes = ['status' => ['old' => 'Planning', 'new' => str($status)->replace('_', ' ')->ucfirst()->toString()]];
                if ($key === 'portal') {
                    $changes = ['status' => ['old' => 'Active', 'new' => 'On hold'], 'priority' => ['old' => 'Medium', 'new' => 'High']];
                }
                $project->activities()->create([
                    'user_id' => $users['maya']->id, 'actor_name' => $users['maya']->name, 'interface' => 'client_portal',
                    'event' => 'project_updated', 'changes' => $changes,
                    'created_at' => now()->subDays(2), 'updated_at' => now()->subDays(2),
                ]);
                $room = hash('sha256', '/projects/' . $project->id);
                $root = $this->message($room, $users['maya'], $key === 'portal'
                    ? 'We are pausing the redesign until security approves document sharing. Leo, can you confirm what remains?'
                    : $description, 46);
                $this->message($room, $users['leo'], $key === 'portal'
                    ? 'The remaining blocker is the retention policy for uploaded documents. I will bring the approval checklist to the next review. Implementation and acceptance sessions depend on this.'
                    : 'Thanks. The task list reflects the current delivery plan; I will post any changes here.', 45, $root);
                if ($key === 'portal') {
                    $question = $this->message($room, $users['sam'], '<p><span data-type="mention" data-id="agent" data-label="Agent" data-char="@">@Agent</span> Why is this project on hold, and what can we do next?</p>', 24);
                    PageMessage::query()->create([
                        'room' => $room, 'parent_id' => $question->id, 'user_id' => null, 'is_agent' => true,
                        'agent_request_id' => $question->id, 'agent_status' => 'completed', 'body_format' => 'text',
                        'body' => "Maya changed the project from **Active → On hold** and raised its priority from **Medium → High** through the client portal.\n\nLeo's update explains the blocker: security still needs to approve the document retention policy.\n\n- Leo owns the security review.\n- Implementation and customer acceptance depend on that approval.\n- The project has spent **$38,000 of $60,000**, leaving **$22,000**.\n\nAsk Leo for the approval date before moving the project back to Active. I have not changed any fields.",
                        'created_at' => now()->subHours(24)->addMinute(), 'updated_at' => now()->subHours(24)->addMinute(),
                    ]);
                    $this->message($room, $users['sam'], 'That matches the customer conversation. Let us keep the hold until Leo confirms approval.', 23, $question);
                    $resolved = $this->message($room, $users['ava'], 'The accessible colour palette is approved. Can we close the visual review?', 72);
                    $this->message($room, $users['maya'], 'Yes, the contrast checks passed. No design changes are needed.', 71, $resolved);
                    $resolved->update(['resolved_at' => now()->subHours(70)]);
                }
            }
        }));
    }

    protected function message(string $room, User $user, string $body, int $hoursAgo, ?PageMessage $parent = null): PageMessage
    {
        return PageMessage::query()->create([
            'room' => $room, 'user_id' => $user->id, 'body' => $body, 'body_format' => str_starts_with($body, '<p>') ? 'html' : 'text',
            'parent_id' => $parent?->id, 'created_at' => now()->subHours($hoursAgo), 'updated_at' => now()->subHours($hoursAgo),
        ]);
    }
}
