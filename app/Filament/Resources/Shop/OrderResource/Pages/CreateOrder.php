<?php

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Filament\Resources\Shop\OrderResource;
use App\Models\Shop\Order;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateOrder extends CreateRecord
{
    use HasWizard;

    protected static string $resource = OrderResource::class;

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return parent::form($form)
            ->components([
                \Filament\Forms\Components\Wizard::make($this->getSteps())
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
        /** @var Order $order */
        $order = $this->record;

        /** @var User $user */
        $user = auth()->user();

        Notification::make()
            ->title('New order')
            ->icon('heroicon-o-shopping-bag')
            ->body("**{$order->customer?->name} ordered {$order->items->count()} products.**")
            ->actions([
                \Filament\Tables\Actions\Action::make('View')
                    ->url(OrderResource::getUrl('edit', ['record' => $order])),
            ])
            ->sendToDatabase($user);
    }

    /** @return \Filament\Forms\Components\Wizard\Step[] */
    protected function getSteps(): array
    {
        return [
            \Filament\Forms\Components\Wizard\Step::make('Order Details')
                ->schema([
                    \Filament\Forms\Components\Section::make()->schema(OrderResource::getDetailsFormSchema())->columns(),
                ]),

            \Filament\Forms\Components\Wizard\Step::make('Order Items')
                ->schema([
                    \Filament\Forms\Components\Section::make()->schema([
                        OrderResource::getItemsRepeater(),
                    ]),
                ]),
        ];
    }
}
