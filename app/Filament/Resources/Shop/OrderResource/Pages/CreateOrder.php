<?php

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Filament\Resources\Shop\OrderResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateOrder extends CreateRecord
{
    use HasWizard;

    protected static string $resource = OrderResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }

    protected function afterCreate(): void
    {
        $order = $this->record;

        Notification::make()
            ->title('New order')
            ->icon('heroicon-o-shopping-bag')
            ->body("**{$order->customer->name} ordered {$order->items->count()} products.**")
            ->actions([
                Action::make('View')
                    ->url(OrderResource::getUrl('edit', ['record' => $order])),
            ])
            ->sendToDatabase(auth()->user());
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Order Details')
                ->schema([
                    Section::make()->schema(OrderResource::getDetailsFormSchema())->columns(),
                ]),

            Step::make('Order Items')
                ->schema([
                    Section::make()->schema([
                        OrderResource::getItemsRepeater(),
                    ]),
                ]),
        ];
    }
}
